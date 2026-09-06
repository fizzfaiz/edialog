<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->foreignId('tagged_sektor_id')->nullable()->after('sektor_pegawai')->constrained('sektors')->nullOnDelete();
            $table->foreignId('tagged_unit_id')->nullable()->after('tagged_sektor_id')->constrained('units')->nullOnDelete();
            $table->foreignId('answered_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tagged_sektor_id');
            $table->dropConstrainedForeignId('tagged_unit_id');
            $table->dropConstrainedForeignId('answered_by');
        });
    }
};