<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin: full access to all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Legacy/tutorial roles (kept for backward compatibility)
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $user = Role::firstOrCreate(['name' => 'User']);

        $admin->syncPermissions([
            'create-user',
            'edit-user',
            'delete-user',
            'view-dialog-prestasi',
            'create-dialog-prestasi',
            'edit-dialog-prestasi',
            'delete-dialog-prestasi',
            'feedback-dialog-prestasi',
        ]);

        $user->syncPermissions([
            'view-dialog-prestasi',
        ]);

        // Domain roles — Dialog Prestasi (KPM / JPN / PPD)
        $jpnAdmin = Role::firstOrCreate(['name' => 'JPN Admin']);
        $jpnAdmin->syncPermissions([
            'view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi',
            'create-user', 'edit-user', 'delete-user',
            'create-role', 'edit-role', 'delete-role',
            'manage-settings',
        ]);

        $jpnUser = Role::firstOrCreate(['name' => 'JPN User']);
        $jpnUser->syncPermissions([
            'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
        ]);

        $ppdAdmin = Role::firstOrCreate(['name' => 'PPD Admin']);
        $ppdAdmin->syncPermissions([
            'view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi',
            'create-user', 'edit-user',
        ]);

        $ppdUser = Role::firstOrCreate(['name' => 'PPD User']);
        $ppdUser->syncPermissions([
            'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
        ]);

        $sektorAdmin = Role::firstOrCreate(['name' => 'Sektor Admin']);
        $sektorAdmin->syncPermissions([
            'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
            'create-user', 'edit-user',
        ]);
    }
}
