<?php

namespace Tests\Feature;

use App\Models\PejabatPendidikan;
use App\Models\Sektor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JpnSectorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_jpn_admin_can_load_sektors_for_a_selected_ppd(): void
    {
        Permission::firstOrCreate(['name' => 'view-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'create-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'edit-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'delete-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'feedback-dialog-prestasi']);

        $role = Role::firstOrCreate(['name' => 'JPN Admin']);
        $role->syncPermissions(['view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi']);

        $jpn = PejabatPendidikan::create(['nama' => 'JPN Melaka', 'kod' => 'JPN-MELAKA', 'jenis' => 'jpn']);
        $ppd = PejabatPendidikan::create(['nama' => 'PPD Melaka Tengah', 'kod' => 'PPD-MT', 'jenis' => 'ppd', 'induk_id' => $jpn->id]);
        $sector = Sektor::create(['nama' => 'Sektor Ujian', 'kod' => 'SEK-UT', 'pejabat_pendidikan_id' => $ppd->id]);

        $user = User::factory()->create([
            'pejabat_pendidikan_id' => $jpn->id,
        ]);
        $user->assignRole($role);

        $response = $this->actingAs($user)->getJson('/api/sektors/' . $ppd->id);

        $response->assertOk();
        $response->assertJsonFragment(['id' => $sector->id, 'nama' => 'Sektor Ujian']);
    }
}
