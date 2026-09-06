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

        // 1. Drop unit_ppds FK and table (from previous migration)
        if (Schema::hasColumn('dp_ppd_issues', 'tagged_unit_ppd_id')) {
            Schema::table('dp_ppd_issues', fn (Blueprint $table) => $this->dropForeignColumn($table, 'tagged_unit_ppd_id'));
        }
        if (Schema::hasColumn('dp_ppd_reports', 'unit_ppd_id')) {
            Schema::table('dp_ppd_reports', fn (Blueprint $table) => $this->dropForeignColumn($table, 'unit_ppd_id'));
        }
        if (Schema::hasColumn('users', 'unit_ppd_id')) {
            Schema::table('users', fn (Blueprint $table) => $this->dropForeignColumn($table, 'unit_ppd_id'));
        }
        Schema::dropIfExists('unit_ppds');

        // 2. Recreate sektors table (FK → pejabat_pendidikans)
        Schema::create('sektors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('pejabat_pendidikan_id')->constrained('pejabat_pendidikans')->cascadeOnDelete();
            $table->timestamps();
        });

        // 3. Recreate units table (FK → sektors)
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('sektor_id')->constrained('sektors')->cascadeOnDelete();
            $table->timestamps();
        });

        // 4. Add sektor_id + unit_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->after('pejabat_pendidikan_id')
                ->constrained('sektors')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('sektor_id')
                ->constrained('units')->nullOnDelete();
        });

        // 5. Add sektor_id + unit_id to dp_ppd_reports
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $table->foreignId('sektor_id')->nullable()->after('pejabat_pendidikan_id')
                ->constrained('sektors')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->after('sektor_id')
                ->constrained('units')->nullOnDelete();
        });

        // 6. Add tagged_sektor_id + tagged_unit_id to dp_ppd_issues
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->foreignId('tagged_sektor_id')->nullable()->after('answered_by')
                ->constrained('sektors')->nullOnDelete();
            $table->foreignId('tagged_unit_id')->nullable()->after('tagged_sektor_id')
                ->constrained('units')->nullOnDelete();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'tagged_unit_id');
            $this->dropForeignColumn($table, 'tagged_sektor_id');
        });
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'unit_id');
            $this->dropForeignColumn($table, 'sektor_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $this->dropForeignColumn($table, 'unit_id');
            $this->dropForeignColumn($table, 'sektor_id');
        });

        Schema::dropIfExists('units');
        Schema::dropIfExists('sektors');

        // Recreate unit_ppds
        Schema::create('unit_ppds', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kod')->unique();
            $table->foreignId('pejabat_pendidikan_id')->constrained('pejabat_pendidikans')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_ppd_id')->nullable()->constrained('unit_ppds')->nullOnDelete();
        });
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $table->foreignId('unit_ppd_id')->nullable()->constrained('unit_ppds')->nullOnDelete();
        });
        Schema::table('dp_ppd_issues', function (Blueprint $table) {
            $table->foreignId('tagged_unit_ppd_id')->nullable()->constrained('unit_ppds')->nullOnDelete();
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