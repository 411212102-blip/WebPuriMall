"""
Python Analytics Worker - Puri Indah Mall Loyalty System

Dependencies:
    pip install pandas scikit-learn mysql-connector-python

Run:
    python analytics/kmeans_worker.py
"""

from __future__ import annotations

import logging
import os
import sys
from dataclasses import dataclass
from pathlib import Path

import mysql.connector
import pandas as pd
from mysql.connector import Error
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler


BASE_DIR = Path(__file__).resolve().parent
LOG_FILE = BASE_DIR / "kmeans_analytics.log"

logging.basicConfig(
    filename=LOG_FILE,
    level=logging.INFO,
    format="%(asctime)s | %(levelname)s | %(message)s",
)
console = logging.StreamHandler(sys.stdout)
console.setLevel(logging.INFO)
console.setFormatter(logging.Formatter("%(levelname)s | %(message)s"))
logging.getLogger().addHandler(console)


@dataclass(frozen=True)
class DbConfig:
    host: str = os.getenv("DB_HOST", "127.0.0.1")
    port: int = int(os.getenv("DB_PORT", "3306"))
    database: str = os.getenv("DB_DATABASE", "puri_indah_mall")
    user: str = os.getenv("DB_USERNAME", "root")
    password: str = os.getenv("DB_PASSWORD", "")


FEATURES = ["recency_days", "frequency", "monetary"]
N_CLUSTERS = 3


def connect(config: DbConfig):
    return mysql.connector.connect(
        host=config.host,
        port=config.port,
        database=config.database,
        user=config.user,
        password=config.password,
        autocommit=False,
    )


def fetch_rfm(connection) -> pd.DataFrame:
    query = """
        SELECT
            id_pelanggan,
            COALESCE(recency_days, 9999) AS recency_days,
            COALESCE(frequency, 0) AS frequency,
            COALESCE(monetary, 0) AS monetary
        FROM vw_rfm_pelanggan
        WHERE id_pelanggan IS NOT NULL
    """
    return pd.read_sql(query, connection)


def build_cluster_mapping(kmeans: KMeans, scaler: StandardScaler) -> dict[int, int]:
    centroid_original = scaler.inverse_transform(kmeans.cluster_centers_)
    centroid_df = pd.DataFrame(centroid_original, columns=FEATURES)
    centroid_df["raw_cluster"] = range(N_CLUSTERS)

    # Master cluster SQL: 1=Platinum, 2=Gold, 3=Dormant.
    ordered = centroid_df.sort_values(
        by=["monetary", "frequency", "recency_days"],
        ascending=[False, False, True],
    ).reset_index(drop=True)

    return {
        int(row.raw_cluster): int(rank + 1)
        for rank, row in ordered.iterrows()
    }


def cluster_customers(df: pd.DataFrame) -> pd.DataFrame:
    if len(df) < N_CLUSTERS:
        raise ValueError(f"Minimal {N_CLUSTERS} pelanggan diperlukan untuk K-Means. Data tersedia: {len(df)}")

    features = df[FEATURES].astype(float)
    scaler = StandardScaler()
    scaled = scaler.fit_transform(features)

    kmeans = KMeans(
        n_clusters=N_CLUSTERS,
        init="k-means++",
        n_init=20,
        random_state=42,
        algorithm="lloyd",
    )

    df = df.copy()
    df["raw_cluster"] = kmeans.fit_predict(scaled)
    cluster_map = build_cluster_mapping(kmeans, scaler)
    df["id_cluster"] = df["raw_cluster"].map(cluster_map).astype(int)

    logging.info("Centroid label mapping: %s", cluster_map)
    return df[["id_pelanggan", "id_cluster"]]


def bulk_update_clusters(connection, result: pd.DataFrame) -> int:
    payload = [
        (int(row.id_cluster), int(row.id_pelanggan))
        for row in result.itertuples(index=False)
    ]

    query = """
        UPDATE pelanggan
        SET id_cluster = %s, updated_at = CURRENT_TIMESTAMP
        WHERE id_pelanggan = %s
    """

    cursor = connection.cursor()
    cursor.executemany(query, payload)
    affected = cursor.rowcount
    connection.commit()
    cursor.close()
    return affected


def main() -> int:
    config = DbConfig()
    logging.info("K-Means worker started. DB=%s:%s/%s", config.host, config.port, config.database)

    connection = None
    try:
        connection = connect(config)
        df = fetch_rfm(connection)

        if df.empty:
            logging.warning("No active customer RFM rows found in vw_rfm_pelanggan.")
            return 0

        logging.info("Fetched %s customer rows from vw_rfm_pelanggan.", len(df))
        result = cluster_customers(df)
        affected = bulk_update_clusters(connection, result)

        summary = result["id_cluster"].value_counts().sort_index().to_dict()
        logging.info("Bulk update completed. affected=%s summary=%s", affected, summary)
        return 0

    except (Error, ValueError) as exc:
        if connection:
            connection.rollback()
        logging.exception("K-Means worker failed: %s", exc)
        return 1
    finally:
        if connection and connection.is_connected():
            connection.close()
            logging.info("Database connection closed.")


if __name__ == "__main__":
    raise SystemExit(main())
