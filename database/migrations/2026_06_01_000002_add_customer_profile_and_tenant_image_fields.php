<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            if (! Schema::hasColumn('pelanggan', 'no_pelanggan')) {
                $table->string('no_pelanggan', 30)->nullable()->unique()->after('id_pelanggan');
            }

            if (! Schema::hasColumn('pelanggan', 'alamat')) {
                $table->string('alamat', 255)->nullable()->after('nama_pelanggan');
            }
        });

        Schema::table('tenant', function (Blueprint $table) {
            if (! Schema::hasColumn('tenant', 'gambar_tenant')) {
                $table->string('gambar_tenant')->nullable()->after('nama_tenant');
            }
        });

        DB::table('pelanggan')
            ->whereNull('no_pelanggan')
            ->orderBy('id_pelanggan')
            ->get(['id_pelanggan', 'tgl_daftar'])
            ->each(function (object $pelanggan): void {
                $year = $pelanggan->tgl_daftar
                    ? date('Y', strtotime($pelanggan->tgl_daftar))
                    : now()->format('Y');

                DB::table('pelanggan')
                    ->where('id_pelanggan', $pelanggan->id_pelanggan)
                    ->update([
                        'no_pelanggan' => sprintf('PIM-%s-%04d', $year, $pelanggan->id_pelanggan),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('tenant', function (Blueprint $table) {
            if (Schema::hasColumn('tenant', 'gambar_tenant')) {
                $table->dropColumn('gambar_tenant');
            }
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            if (Schema::hasColumn('pelanggan', 'no_pelanggan')) {
                $table->dropUnique(['no_pelanggan']);
                $table->dropColumn('no_pelanggan');
            }

            if (Schema::hasColumn('pelanggan', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
