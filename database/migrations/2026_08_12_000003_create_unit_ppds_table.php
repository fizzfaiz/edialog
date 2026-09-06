<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_ppds', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('pejabat_pendidikan_id')->constrained('pejabat_pendidikans')->cascadeOnDelete();
            $table->timestamps();
        });

        // Add unit_ppd_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_ppd_id')->nullable()->after('pejabat_pendidikan_id')
                ->constrained('unit_ppds')->nullOnDelete();
        });

        // Add unit_ppd_id to dp_ppd_reports
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $table->foreignId('unit_ppd_id')->nullable()->after('pejabat_pendidikan_id')
                ->constrained('unit_ppds')->nullOnDelete();
        });

        // Add tagged_unit_ppd_id to dp_ppd_issues (for feedback targeting)
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->foreignId('tagged_unit_ppd_id')->nullable()->after('answered_by')
                ->constrained('unit_ppds')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'unit_ppd_id');
        });
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'unit_ppd_id');
        });
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'tagged_unit_ppd_id');
        });
        Schema::dropIfExists('unit_ppds');
    }

    /**
     * Drop a column together with its foreign key constraint.
     * SQLite has no DROP FOREIGN KEY; dropping the column (via table
     * recreation) removes the constraint automatically.
     */
    private function dropForeignColumn(Blueprint $table, string $column): void
    {
        if (DB::getDriverName() === 'mysql') {
            $table->dropConstrainedForeignId($column);
        } else {
            $table->dropColumn($column);
        }
    }
};