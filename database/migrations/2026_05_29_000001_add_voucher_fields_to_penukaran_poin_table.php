<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('penukaran_poin', 'status_redeem')) {
            DB::statement("ALTER TABLE penukaran_poin MODIFY status_redeem VARCHAR(50) NOT NULL DEFAULT 'Success'");
        } else {
            Schema::table('penukaran_poin', function (Blueprint $table) {
                $table->string('status_redeem', 50)->default('Success')->after('poin_terpotong');
            });
        }

        Schema::table('penukaran_poin', function (Blueprint $table) {
            if (! Schema::hasColumn('penukaran_poin', 'voucher_code')) {
                $table->string('voucher_code', 80)->nullable()->unique()->after('status_redeem');
            }

            if (! Schema::hasColumn('penukaran_poin', 'claimed_at')) {
                $table->timestamp('claimed_at')->nullable()->after('voucher_code');
            }

            if (! Schema::hasColumn('penukaran_poin', 'claimed_by')) {
                $table->unsignedInteger('claimed_by')->nullable()->after('claimed_at');
                $table->foreign('claimed_by')
                    ->references('id_pegawai')
                    ->on('pegawai')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });

        DB::statement("
            UPDATE penukaran_poin
            SET voucher_code = CONCAT('PIM-', id_redeem, '-', DATE_FORMAT(tanggal_redeem, '%Y%m%d%H%i%s'))
            WHERE voucher_code IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('penukaran_poin', function (Blueprint $table) {
            if (Schema::hasColumn('penukaran_poin', 'claimed_by')) {
                $table->dropForeign(['claimed_by']);
                $table->dropColumn('claimed_by');
            }
            if (Schema::hasColumn('penukaran_poin', 'claimed_at')) {
                $table->dropColumn('claimed_at');
            }
            if (Schema::hasColumn('penukaran_poin', 'voucher_code')) {
                $table->dropUnique(['voucher_code']);
                $table->dropColumn('voucher_code');
            }
        });
    }
};
