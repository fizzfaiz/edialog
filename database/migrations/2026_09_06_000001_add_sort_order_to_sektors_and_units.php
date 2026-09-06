<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sektors', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('pejabat_pendidikan_id');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('sektor_id');
        });
    }

    public function down(): void
    {
        Schema::table('sektors', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
