<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PejabatPendidikan;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create PPD offices if not exist
        $ppdMt = PejabatPendidikan::firstOrCreate(
            ['kod' => 'PPDMT'],
            ['nama' => 'PPD Melaka Tengah', 'jenis' => 'ppd']
        );
        $ppdAg = PejabatPendidikan::firstOrCreate(
            ['kod' => 'PPDAG'],
            ['nama' => 'PPD Alor Gajah', 'jenis' => 'ppd']
        );
        $ppdJs = PejabatPendidikan::firstOrCreate(
            ['kod' => 'PPDJS'],
            ['nama' => 'PPD Jasin', 'jenis' => 'ppd']
        );

        echo "PPD IDs: {$ppdMt->id}, {$ppdAg->id}, {$ppdJs->id}\n";

        // Ensure PPD role exists
        $ppdRole = Role::firstOrCreate(['name' => 'PPD User']);

        // Get max user ID to manually assign IDs
        $maxId = DB::table('users')->max('id') ?? 0;

        $users = [
            [
                'id' => $maxId + 1,
                'name' => 'Admin PPD Melaka Tengah',
                'email' => 'ppdmt@example.com',
                'pejabat_pendidikan_id' => $ppdMt->id,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $maxId + 2,
                'name' => 'Admin PPD Alor Gajah',
                'email' => 'ppdag@example.com',
                'pejabat_pendidikan_id' => $ppdAg->id,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => $maxId + 3,
                'name' => 'Admin PPD Jasin',
                'email' => 'ppj@example.com',
                'pejabat_pendidikan_id' => $ppdJs->id,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $data) {
            // Check if email already exists
            $existing = DB::table('users')->where('email', $data['email'])->first();
            if ($existing) {
                echo "Already exists: {$data['email']} (ID: {$existing->id})\n";
                continue;
            }

            DB::table('users')->insert($data);
            echo "Created: {$data['email']} (ID: {$data['id']})\n";

            // Assign role via spatie's model_has_roles
            DB::table('model_has_roles')->insert([
                'role_id' => $ppdRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $data['id'],
            ]);
        }

        echo "Done!\n";
    }
}