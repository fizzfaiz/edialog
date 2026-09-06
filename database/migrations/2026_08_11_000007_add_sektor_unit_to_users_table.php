<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->after('pejabat_pendidikan_id')->constrained('sektors')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('sektor_id')->constrained('units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_id');
            $table->dropConstrainedForeignId('sektor_id');
        });
    }
};