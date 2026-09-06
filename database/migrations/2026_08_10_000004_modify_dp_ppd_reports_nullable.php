<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            // Allow nullable for all string/text columns to support autosave partial data
            $table->string('pengerusi')->nullable()->default('')->change();
            $table->date('tarikh')->nullable()->change();
            $table->string('hari')->nullable()->default('')->change();
            $table->string('masa')->nullable()->default('00:00')->change();
            $table->string('tempat')->nullable()->default('')->change();
            $table->string('dicatat_oleh')->nullable()->default('')->change();
            $table->string('jawatan_pencatat')->nullable()->default('')->change();
            $table->string('disahkan_oleh')->nullable()->default('')->change();
            $table->string('jawatan_pengesah')->nullable()->default('')->change();
        });

        // Check and fix kehadiran_a column if it exists
        if (Schema::hasColumn('dp_ppd_reports', 'kehadiran_a')) {
            Schema::table('dp_ppd_reports', function (Blueprint $table) {
                $table->string('kehadiran_a')->nullable()->default('')->change();
            });
        }

        // Check and fix any other possibly unknown columns
        $possibleColumns = ['kehadiran_b', 'kehadiran_c', 'kehadiran_d', 'isu_a', 'isu_b', 'isu_c', 'isu_d', 'tindakan_a', 'tindakan_b', 'tindakan_c', 'tindakan_d', 'sektor_a', 'sektor_b', 'sektor_c', 'sektor_d'];

        foreach ($possibleColumns as $col) {
            if (Schema::hasColumn('dp_ppd_reports', $col)) {
                Schema::table('dp_ppd_reports', function (Blueprint $table) use ($col) {
                    $table->string($col)->nullable()->default('')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dp_ppd_reports', function (Blueprint $table) {
            $table->string('pengerusi')->nullable(false)->change();
            $table->date('tarikh')->nullable(false)->change();
            $table->string('hari')->nullable(false)->change();
            $table->string('masa')->nullable(false)->change();
            $table->string('tempat')->nullable(false)->change();
            $table->string('dicatat_oleh')->nullable(false)->change();
            $table->string('jawatan_pencatat')->nullable(false)->change();
            $table->string('disahkan_oleh')->nullable(false)->change();
            $table->string('jawatan_pengesah')->nullable(false)->change();
        });
    }
};