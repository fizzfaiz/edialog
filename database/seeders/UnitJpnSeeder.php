<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sektor;
use App\Models\Unit;

class UnitJpnSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Sektor: SEKTOR PENGURUSAN SEKOLAH (JPN-S03)
            'JPN-S03' => [
                'UNIT PRASEKOLAH DAN RENDAH',
                'UNIT MENENGAH DAN TINGKATAN 6',
                'UNIT PENDIDIKAN SWASTA',
                'UNIT SEKOLAH JENIS KHAS',
            ],
            // Sektor: SEKTOR PEMBELAJARAN (JPN-S04)
            'JPN-S04' => [
                'UNIT SAINS DAN MATEMATIK',
                'UNIT SAINS SOSIAL',
                'UNIT TEKNIK DAN VOKASIONAL',
                'UNIT BAHASA',
                'UNIT TEKNOLOGI MAKLUMAT KOMUNIKASI',
            ],
            // Sektor: SEKTOR PEMBANGUNAN MURID (JPN-S05)
            'JPN-S05' => [
                'UNIT HAL EHWAL MURID',
                'UNIT PEMBANGUNAN BAKAT MURID',
                'PUSAT KOKURIKULUM',
            ],
            // Sektor: SEKTOR PENDIDIKAN ISLAM (JPN-S06)
            'JPN-S06' => [
                'UNIT PERANCANGAN DAN PEMBANGUNAN PENDIDIKAN ISLAM',
                'UNIT PEMBANGUNAN ADAB DAN NILAI',
            ],
            // Sektor: SEKTOR PENDIDIKAN KHAS (JPN-S07)
            'JPN-S07' => [
                'UNIT PENGURUSAN PENDIDIKAN KHAS',
                'PUSAT PERKHIDMATAN PENDIDIKAN KHAS (3PK)',
            ],
            // Sektor: SEKTOR INFRASTRUKTUR DAN PEROLEHAN (JPN-S08)
            'JPN-S08' => [
                'UNIT PEMBANGUNAN DAN PENGURUSAN INFRASTRUKTUR',
                'UNIT PEROLEHAN',
            ],
            // Sektor: SEKTOR SUMBER MANUSIA (JPN-S09)
            'JPN-S09' => [
                'KPP UNIT PENGURUSAN BAKAT',
                'SUB UNIT NAIK PANGKAT',
                'SUB UNIT LATIHAN',
                'SUB UNIT PENILAIAN & KOMPETENSI',
                'UNIT PERJAWATAN DAN PERKHIDMATAN',
            ],
            // Sektor: SEKTOR PENGURUSAN (JPN-S10)
            'JPN-S10' => [
                'UNIT KEWANGAN',
                'UNIT AKAUN',
                'UNIT PENTADBIRAN',
            ],
        ];

        foreach ($data as $kodSektor => $units) {
            $sektor = Sektor::where('kod', $kodSektor)->first();

            if (!$sektor) {
                echo "SKIP: Sektor kod '{$kodSektor}' tidak dijumpai.\n";
                continue;
            }

            echo "--- {$sektor->nama} (ID: {$sektor->id}) ---\n";

            foreach ($units as $index => $nama) {
                $kod = $kodSektor . '-U' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                $unit = Unit::firstOrCreate(
                    ['kod' => $kod],
                    [
                        'nama' => $nama,
                        'sektor_id' => $sektor->id,
                    ]
                );

                echo ($unit->wasRecentlyCreated ? '  CREATED' : '  EXISTS') . ": {$nama} (Kod: {$kod})\n";
            }
        }

        echo "Done! Total Units: " . Unit::count() . "\n";
    }
}