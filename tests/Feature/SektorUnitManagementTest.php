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

class SektorUnitManagementTest extends TestCase
{
    use RefreshDatabase;

    private function ppdAdmin(): User
    {
        Permission::firstOrCreate(['name' => 'manage-sektor-unit']);
        $role = Role::firstOrCreate(['name' => 'PPD Admin']);
        $role->syncPermissions(['manage-sektor-unit']);

        $ppd = PejabatPendidikan::create(['nama' => 'PPD Melaka Tengah', 'kod' => 'PPD-MT', 'jenis' => 'ppd']);

        $user = User::factory()->create(['pejabat_pendidikan_id' => $ppd->id]);
        $user->assignRole($role);

        return $user;
    }

    public function test_ppd_admin_can_create_and_reorder_sektor(): void
    {
        $user = $this->ppdAdmin();

        $this->actingAs($user)->get(route('sektor.index'))->assertOk();
        $this->actingAs($user)->get(route('sektor.create'))->assertOk();

        $this->actingAs($user)->post(route('sektor.store'), [
            'nama' => 'Sektor Baru',
            'kod' => 'PPDMT-99',
            'pejabat_pendidikan_id' => $user->pejabat_pendidikan_id,
        ])->assertRedirect(route('sektor.index'));

        $sektor = Sektor::where('kod', 'PPDMT-99')->first();
        $this->assertNotNull($sektor);

        $this->actingAs($user)->postJson(route('sektor.reorder'), ['order' => [$sektor->id]])
            ->assertOk();

        $this->assertDatabaseHas('sektors', ['id' => $sektor->id, 'sort_order' => 0]);
    }

    public function test_ppd_admin_can_create_unit_under_sektor(): void
    {
        $user = $this->ppdAdmin();

        $sektor = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDMT-01', 'pejabat_pendidikan_id' => $user->pejabat_pendidikan_id]);

        $this->actingAs($user)->post(route('unit.store'), [
            'nama' => 'Unit Rendah',
            'kod' => 'PPDMT01-01',
            'sektor_id' => $sektor->id,
        ])->assertRedirect(route('unit.index'));

        $this->assertDatabaseHas('units', ['kod' => 'PPDMT01-01', 'sektor_id' => $sektor->id]);

        $unit = Unit::where('kod', 'PPDMT01-01')->first();
        $this->actingAs($user)->postJson(route('unit.reorder'), ['order' => [$unit->id]])->assertOk();
    }

    public function test_ppd_admin_can_move_unit_to_another_sektor(): void
    {
        $user = $this->ppdAdmin();

        $sektor1 = Sektor::create(['nama' => 'Sektor Akademik', 'kod' => 'PPDMT-01', 'pejabat_pendidikan_id' => $user->pejabat_pendidikan_id]);
        $sektor2 = Sektor::create(['nama' => 'Sektor Kewangan', 'kod' => 'PPDMT-02', 'pejabat_pendidikan_id' => $user->pejabat_pendidikan_id]);

        $unit = Unit::create(['nama' => 'Unit Rendah', 'kod' => 'PPDMT01-01', 'sektor_id' => $sektor1->id]);

        $this->actingAs($user)->postJson(route('unit.reorder'), [
            'sektor_id' => $sektor2->id,
            'order' => [$unit->id],
        ])->assertOk();

        $this->assertDatabaseHas('units', ['id' => $unit->id, 'sektor_id' => $sektor2->id, 'sort_order' => 0]);
    }
}
