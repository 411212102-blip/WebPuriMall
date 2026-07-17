<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('penukaran_poin', 'status_redeem')) {
            Schema::table('penukaran_poin', function (Blueprint $table) {
                $table->enum('status_redeem', ['Pending', 'Success'])
                    ->default('Success')
                    ->after('poin_terpotong');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('penukaran_poin', 'status_redeem')) {
            Schema::table('penukaran_poin', function (Blueprint $table) {
                $table->dropColumn('status_redeem');
            });
        }
    }
};
