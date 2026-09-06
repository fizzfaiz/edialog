<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->text('fokus')->nullable()->after('isu');
        });
    }

    public function down(): void
    {
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->dropColumn('fokus');
        });
    }
};