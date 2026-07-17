<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            if (! Schema::hasColumn('event', 'gambar_event')) {
                $table->string('gambar_event')->nullable()->after('nama_event');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            if (Schema::hasColumn('event', 'gambar_event')) {
                $table->dropColumn('gambar_event');
            }
        });
    }
};
