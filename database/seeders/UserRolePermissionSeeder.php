<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\PejabatPendidikan;
use App\Models\Sektor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ===== PERMISSIONS =====
        $permissions = [
            // Dialog Prestasi (Dialog Prestasi)
            'view-dialog-prestasi',
            'create-dialog-prestasi',
            'edit-dialog-prestasi',
            'delete-dialog-prestasi',
            'feedback-dialog-prestasi',
            // User management
            'create-user',
            'edit-user',
            'delete-user',
            // Role management
            'create-role',
            'edit-role',
            'delete-role',
            // Settings
            'manage-settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        echo "Permissions: " . count($permissions) . " created.\n";

        // ===== ROLES =====
        $roles = [
            'Super Admin' => $permissions, // all permissions

            'JPN Admin' => [
                'view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi',
                'create-user', 'edit-user', 'delete-user',
                'create-role', 'edit-role', 'delete-role',
                'manage-settings',
            ],

            'JPN User' => [
                'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
            ],

            'PPD Admin' => [
                'view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi',
                'create-user', 'edit-user',
            ],

            'PPD User' => [
                'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
            ],

            'Sektor Admin' => [
                'view-dialog-prestasi', 'create-dialog-prestasi', 'feedback-dialog-prestasi',
                'create-user', 'edit-user',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
            echo "Role: {$roleName} (" . count($perms) . " permissions)\n";
        }

        // ===== PEJABAT =====
        $jpnMelaka = PejabatPendidikan::where('kod', 'JPN-MELAKA')
            ->orWhere(function ($query) {
                $query->where('nama', 'JPN Melaka')->where('jenis', 'jpn');
            })
            ->first();

        if (!$jpnMelaka) {
            $jpnMelaka = PejabatPendidikan::create([
                'kod' => 'JPN-MELAKA',
                'nama' => 'JPN Melaka',
                'jenis' => 'jpn',
            ]);
        } else {
            $jpnMelaka->update(['kod' => 'JPN-MELAKA', 'nama' => 'JPN Melaka', 'jenis' => 'jpn']);
        }

        $ppdMelakaTengah = PejabatPendidikan::where('kod', 'PPD-MT')
            ->orWhere(function ($query) {
                $query->where('nama', 'PPD Melaka Tengah')->where('jenis', 'ppd');
            })
            ->first();
        if (!$ppdMelakaTengah) {
            $ppdMelakaTengah = PejabatPendidikan::create([
                'kod' => 'PPD-MT',
                'nama' => 'PPD Melaka Tengah',
                'jenis' => 'ppd',
                'induk_id' => $jpnMelaka->id,
            ]);
        } else {
            $ppdMelakaTengah->update(['kod' => 'PPD-MT', 'nama' => 'PPD Melaka Tengah', 'jenis' => 'ppd', 'induk_id' => $jpnMelaka->id]);
        }

        $ppdAlorGajah = PejabatPendidikan::where('kod', 'PPD-AG')
            ->orWhere(function ($query) {
                $query->where('nama', 'PPD Alor Gajah')->where('jenis', 'ppd');
            })
            ->first();
        if (!$ppdAlorGajah) {
            $ppdAlorGajah = PejabatPendidikan::create([
                'kod' => 'PPD-AG',
                'nama' => 'PPD Alor Gajah',
                'jenis' => 'ppd',
                'induk_id' => $jpnMelaka->id,
            ]);
        } else {
            $ppdAlorGajah->update(['kod' => 'PPD-AG', 'nama' => 'PPD Alor Gajah', 'jenis' => 'ppd', 'induk_id' => $jpnMelaka->id]);
        }

        $ppdJasin = PejabatPendidikan::where('kod', 'PPD-JS')
            ->orWhere(function ($query) {
                $query->where('nama', 'PPD Jasin')->where('jenis', 'ppd');
            })
            ->first();
        if (!$ppdJasin) {
            $ppdJasin = PejabatPendidikan::create([
                'kod' => 'PPD-JS',
                'nama' => 'PPD Jasin',
                'jenis' => 'ppd',
                'induk_id' => $jpnMelaka->id,
            ]);
        } else {
            $ppdJasin->update(['kod' => 'PPD-JS', 'nama' => 'PPD Jasin', 'jenis' => 'ppd', 'induk_id' => $jpnMelaka->id]);
        }
        echo "Pejabats: JPN Melaka ({$jpnMelaka->id}), PPD MT ({$ppdMelakaTengah->id}), PPD AG ({$ppdAlorGajah->id}), PPD JS ({$ppdJasin->id})\n";

        // ===== SEKTOR (untuk user assignment) =====
        $sektorJpn = Sektor::where('pejabat_pendidikan_id', $jpnMelaka->id)->first();
        // Get (or create) first sektor for each PPD so users can be linked to a sektor
        $sektorMT = Sektor::where('pejabat_pendidikan_id', $ppdMelakaTengah->id)->first();
        if (! $sektorMT) {
            $sektorMT = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDMT-01', 'pejabat_pendidikan_id' => $ppdMelakaTengah->id]);
        }
        $sektorAG = Sektor::where('pejabat_pendidikan_id', $ppdAlorGajah->id)->first();
        if (! $sektorAG) {
            $sektorAG = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDAG-01', 'pejabat_pendidikan_id' => $ppdAlorGajah->id]);
        }
        $sektorJS = Sektor::where('pejabat_pendidikan_id', $ppdJasin->id)->first();
        if (! $sektorJS) {
            $sektorJS = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDJS-01', 'pejabat_pendidikan_id' => $ppdJasin->id]);
        }

        // ===== USERS =====
        $users = [
            // Super Admin (global access, not bound to any office)
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => null,
                'sektor_id' => null,
                'role' => 'Super Admin',
            ],
            // JPN
            [
                'name' => 'Admin JPN Melaka',
                'email' => 'admin_jpn@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $jpnMelaka->id,
                'sektor_id' => $sektorJpn?->id,
                'role' => 'JPN Admin',
            ],
            [
                'name' => 'Pengguna JPN Melaka',
                'email' => 'jpn_user@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $jpnMelaka->id,
                'sektor_id' => null,
                'role' => 'JPN User',
            ],
            // PPD Melaka Tengah
            [
                'name' => 'Admin PPD Melaka Tengah',
                'email' => 'admin_ppdmt@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdMelakaTengah->id,
                'sektor_id' => $sektorMT?->id,
                'role' => 'PPD Admin',
            ],
            [
                'name' => 'Pengguna PPD Melaka Tengah',
                'email' => 'ppd_user_mt@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdMelakaTengah->id,
                'sektor_id' => $sektorMT?->id,
                'role' => 'PPD User',
            ],
            // PPD Alor Gajah
            [
                'name' => 'Admin PPD Alor Gajah',
                'email' => 'admin_ppdag@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdAlorGajah->id,
                'sektor_id' => $sektorAG?->id,
                'role' => 'PPD Admin',
            ],
            [
                'name' => 'Pengguna PPD Alor Gajah',
                'email' => 'ppd_user_ag@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdAlorGajah->id,
                'sektor_id' => $sektorAG?->id,
                'role' => 'PPD User',
            ],
            // PPD Jasin
            [
                'name' => 'Admin PPD Jasin',
                'email' => 'admin_ppdjs@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdJasin->id,
                'sektor_id' => $sektorJS?->id,
                'role' => 'PPD Admin',
            ],
            [
                'name' => 'Pengguna PPD Jasin',
                'email' => 'ppd_user_js@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdJasin->id,
                'sektor_id' => $sektorJS?->id,
                'role' => 'PPD User',
            ],
            // Sektor Admin (PPD Melaka Tengah)
            [
                'name' => 'Admin Sektor PPD Melaka Tengah',
                'email' => 'sektor_admin_mt@example.com',
                'password' => 'password123',
                'pejabat_pendidikan_id' => $ppdMelakaTengah->id,
                'sektor_id' => $sektorMT->id,
                'role' => 'Sektor Admin',
            ],
        ];

        $countCreated = 0;
        foreach ($users as $data) {
            $roleName = $data['role'];
            unset($data['role']);

            $existing = User::where('email', $data['email'])->first();
            if ($existing) {
                $existing->update([
                    'pejabat_pendidikan_id' => $data['pejabat_pendidikan_id'],
                    'sektor_id' => $data['sektor_id'],
                ]);
                $existing->syncRoles([$roleName]);
                echo "UPDATED: {$data['email']} (Role: {$roleName}, Office: {$data['pejabat_pendidikan_id']})\n";
            } else {
                $maxId = DB::table('users')->max('id') ?? 0;
                $data['id'] = $maxId + 1;
                $data['password'] = Hash::make($data['password']);
                $data['created_at'] = now();
                $data['updated_at'] = now();
                DB::table('users')->insert($data);

                // Assign role
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->id,
                        'model_type' => 'App\Models\User',
                        'model_id' => $data['id'],
                    ]);
                }
                echo "CREATED: {$data['email']} (Role: {$roleName}, Office: {$data['pejabat_pendidikan_id']})\n";
                $countCreated++;
            }
        }

        echo "\n=== RINGKASAN ===\n";
        echo "Permissions: " . Permission::count() . "\n";
        echo "Roles: " . Role::count() . "\n";
        echo "Users: " . User::count() . " (" . $countCreated . " baru)\n";
        echo "Pejabats: " . PejabatPendidikan::count() . "\n";
    }
}