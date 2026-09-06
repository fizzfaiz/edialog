<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename DP PPD tables and permission names to "Dialog Prestasi".
     */
    public function up(): void
    {
        // 1. Rename tables
        if (Schema::hasTable('dp_ppd_reports') && !Schema::hasTable('dialog_prestasi_reports')) {
            Schema::rename('dp_ppd_reports', 'dialog_prestasi_reports');
        }
        if (Schema::hasTable('dp_ppd_issues') && !Schema::hasTable('dialog_prestasi_issues')) {
            Schema::rename('dp_ppd_issues', 'dialog_prestasi_issues');
        }
        if (Schema::hasTable('dp_ppd_attendances') && !Schema::hasTable('dialog_prestasi_attendances')) {
            Schema::rename('dp_ppd_attendances', 'dialog_prestasi_attendances');
        }

        // 2. Rename permissions
        $map = [
            'view-dp-ppd' => 'view-dialog-prestasi',
            'create-dp-ppd' => 'create-dialog-prestasi',
            'edit-dp-ppd' => 'edit-dialog-prestasi',
            'delete-dp-ppd' => 'delete-dialog-prestasi',
            'feedback-dp-ppd' => 'feedback-dialog-prestasi',
        ];

        foreach ($map as $old => $new) {
            DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }
    }

    /**
     * Reverse the operation.
     */
    public function down(): void
    {
        // Reverse permissions
        $map = [
            'view-dialog-prestasi' => 'view-dp-ppd',
            'create-dialog-prestasi' => 'create-dp-ppd',
            'edit-dialog-prestasi' => 'edit-dp-ppd',
            'delete-dialog-prestasi' => 'delete-dp-ppd',
            'feedback-dialog-prestasi' => 'feedback-dp-ppd',
        ];

        foreach ($map as $old => $new) {
            DB::table('permissions')->where('name', $old)->update(['name' => $new]);
        }

        // Reverse tables
        if (Schema::hasTable('dialog_prestasi_attendances') && !Schema::hasTable('dp_ppd_attendances')) {
            Schema::rename('dialog_prestasi_attendances', 'dp_ppd_attendances');
        }
        if (Schema::hasTable('dialog_prestasi_issues') && !Schema::hasTable('dp_ppd_issues')) {
            Schema::rename('dialog_prestasi_issues', 'dp_ppd_issues');
        }
        if (Schema::hasTable('dialog_prestasi_reports') && !Schema::hasTable('dp_ppd_reports')) {
            Schema::rename('dialog_prestasi_reports', 'dp_ppd_reports');
        }
    }
};
