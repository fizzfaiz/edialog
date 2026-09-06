<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserRolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeedPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_default_users_use_standardized_password(): void
    {
        $this->seed(UserRolePermissionSeeder::class);

        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $this->assertNotNull($superAdmin);
        $this->assertTrue(Hash::check('password123', $superAdmin->password));

        $user = User::where('email', 'admin_jpn@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
