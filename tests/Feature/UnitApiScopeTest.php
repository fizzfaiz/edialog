<?php

namespace Tests\Feature;

use App\Models\PejabatPendidikan;
use App\Models\Sektor;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UnitApiScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppd_user_can_load_units_for_any_sektor_in_their_ppd(): void
    {
        Permission::firstOrCreate(['name' => 'view-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'create-dialog-prestasi']);

        $role = Role::firstOrCreate(['name' => 'PPD User']);
        $role->syncPermissions(['view-dialog-prestasi', 'create-dialog-prestasi']);

        $ppd = PejabatPendidikan::create(['nama' => 'PPD Melaka Tengah', 'kod' => 'PPD-MT', 'jenis' => 'ppd']);
        $sector1 = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDMT-01', 'pejabat_pendidikan_id' => $ppd->id]);
        $sector2 = Sektor::create(['nama' => 'Sektor Pembangunan Murid', 'kod' => 'PPDMT-02', 'pejabat_pendidikan_id' => $ppd->id]);
        $unit = Unit::create(['nama' => 'Unit Rendah', 'kod' => 'PPDMT01-01', 'sektor_id' => $sector1->id]);

        $user = User::factory()->create([
            'pejabat_pendidikan_id' => $ppd->id,
            'sektor_id' => $sector1->id,
        ]);
        $user->assignRole($role);

        // Units for the user's own sektor.
        $response = $this->actingAs($user)->getJson('/api/units/' . $sector1->id);
        $response->assertOk();
        $response->assertJsonFragment(['id' => $unit->id, 'nama' => 'Unit Rendah']);

        // Units for a DIFFERENT sektor in the same PPD (previously returned 403).
        $response2 = $this->actingAs($user)->getJson('/api/units/' . $sector2->id);
        $response2->assertOk();
    }
}
