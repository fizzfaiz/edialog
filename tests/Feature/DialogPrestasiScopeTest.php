<?php

namespace Tests\Feature;

use App\Models\DialogPrestasiIssue;
use App\Models\DialogPrestasiReport;
use App\Models\PejabatPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DialogPrestasiScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_jpn_admin_can_access_feedback_for_its_own_jpn_office_report(): void
    {
        Permission::firstOrCreate(['name' => 'feedback-dialog-prestasi']);
        Permission::firstOrCreate(['name' => 'view-dialog-prestasi']);

        $role = Role::firstOrCreate(['name' => 'JPN Admin']);
        $role->syncPermissions(['feedback-dialog-prestasi', 'view-dialog-prestasi']);

        $jpn = PejabatPendidikan::create([
            'nama' => 'JPN Melaka',
            'kod' => 'JPNMLK',
            'jenis' => 'jpn',
        ]);

        $report = DialogPrestasiReport::create([
            'pejabat_pendidikan_id' => $jpn->id,
            'pengerusi' => 'Pengerusi Ujian',
            'kategori' => 'Dialog Prestasi',
            'tarikh' => '2026-08-27',
            'hari' => 'Rabu',
            'masa' => '09:00:00',
            'tempat' => 'Bilik Mesyuarat',
            'dicatat_oleh' => 'Pembuat Ujian',
            'jawatan_pencatat' => 'Pegawai',
            'disahkan_oleh' => 'Pengesah Ujian',
            'jawatan_pengesah' => 'Ketua',
        ]);

        DialogPrestasiIssue::create([
            'report_id' => $report->id,
            'bil' => 1,
            'isu' => 'Ujian isu',
            'fokus' => 'Fokus',
            'tindakan' => 'Tindakan',
            'sektor_pegawai' => 'Sektor Ujian',
            'jawapan' => '',
            'status' => '',
        ]);

        $user = User::create([
            'name' => 'Admin JPN',
            'email' => 'admin.jpn@test.com',
            'password' => bcrypt('password'),
            'pejabat_pendidikan_id' => $jpn->id,
        ]);
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('dialog-prestasi.feedback', $report));

        $response->assertStatus(200);
    }
}
