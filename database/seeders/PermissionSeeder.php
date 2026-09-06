<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'view-dialog-prestasi',
            'create-dialog-prestasi',
            'edit-dialog-prestasi',
            'delete-dialog-prestasi',
            'feedback-dialog-prestasi',
            'manage-settings',
            'manage-sektor-unit',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        // Using firstOrCreate to avoid duplicate entry errors on re-run
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
