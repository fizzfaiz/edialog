<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        // 1. Drop constrained columns from dp_ppd_issues
        if (Schema::hasColumn('dp_ppd_issues', 'tagged_unit_id')) {
            Schema::table('dp_ppd_issues', fn (Blueprint $table) => $this->dropForeignColumn($table, 'tagged_unit_id'));
        }
        if (Schema::hasColumn('dp_ppd_issues', 'tagged_sektor_id')) {
            Schema::table('dp_ppd_issues', fn (Blueprint $table) => $this->dropForeignColumn($table, 'tagged_sektor_id'));
        }

        // 2. Drop constrained columns from dp_ppd_reports
        if (Schema::hasColumn('dp_ppd_reports', 'unit_id')) {
            Schema::table('dp_ppd_reports', fn (Blueprint $table) => $this->dropForeignColumn($table, 'unit_id'));
        }
        if (Schema::hasColumn('dp_ppd_reports', 'sektor_id')) {
            Schema::table('dp_ppd_reports', fn (Blueprint $table) => $this->dropForeignColumn($table, 'sektor_id'));
        }

        // 3. Drop constrained columns from users
        if (Schema::hasColumn('users', 'unit_id')) {
            Schema::table('users', fn (Blueprint $table) => $this->dropForeignColumn($table, 'unit_id'));
        }
        if (Schema::hasColumn('users', 'sektor_id')) {
            Schema::table('users', fn (Blueprint $table) => $this->dropForeignColumn($table, 'sektor_id'));
        }

        // 4. Drop FK & column from subunits if exists
        if (DB::getDriverName() === 'mysql' && Schema::hasTable('subunits') && Schema::hasColumn('subunits', 'unit_id')) {
            DB::statement('ALTER TABLE `subunits` DROP FOREIGN KEY `sub_units_unit_id_foreign`');
            DB::statement('ALTER TABLE `subunits` DROP COLUMN `unit_id`');
        }

        // 5. Drop units table
        Schema::dropIfExists('units');

        // 6. Drop sektors table
        Schema::dropIfExists('sektors');

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Schema::create('sektors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('pejabat_pendidikan_id')->constrained('pejabat_pendidikans')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('sektor_id')->constrained('sektors')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->constrained('sektors')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
        });

        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->constrained('sektors')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
        });

        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->foreignId('tagged_sektor_id')->nullable()->constrained('sektors')->nullOnDelete();
            $table->foreignId('tagged_unit_id')->nullable()->constrained('units')->nullOnDelete();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
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