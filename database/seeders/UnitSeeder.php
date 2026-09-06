<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Sektor;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $sektors = Sektor::all();

        $unitMap = [
            'Sektor Akademik' => [
                'Unit Rendah',
                'Unit Menengah',
                'Unit Pra Sekolah',
            ],
            'Sektor Pembangunan Murid' => [
                'Unit Kokurikulum',
                'Unit Bimbingan Kaunseling',
            ],
            'Sektor Pengurusan Sekolah' => [
                'Unit Pendaftaran',
                'Unit Inspektorat',
            ],
            'Sektor Teknologi Maklumat dan Komunikasi' => [
                'Unit Infrastruktur',
                'Unit Aplikasi',
                'Unit Data',
            ],
            'Sektor Pendidikan Islam' => [
                'Unit Rendah',
                'Unit Menengah',
            ],
            'Sektor Sumber Manusia' => [
                'Unit Perkhidmatan',
                'Unit Latihan',
            ],
            'Sektor Kewangan dan Akaun' => [
                'Unit Akaun',
                'Unit Perolehan',
            ],
        ];

        foreach ($sektors as $sektor) {
            $name = $sektor->nama;
            if (!isset($unitMap[$name])) {
                continue;
            }

            foreach ($unitMap[$name] as $index => $unitName) {
                $kod = strtoupper(str_replace(['-', ' ', '&', '/', '.'], '', $sektor->kod)) . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                $unit = Unit::where('kod', $kod)->first();
                if (!$unit) {
                    $unit = Unit::where('nama', $unitName)->where('sektor_id', $sektor->id)->first();
                }

                if ($unit) {
                    $unit->update([
                        'kod' => $kod,
                        'nama' => $unitName,
                        'sektor_id' => $sektor->id,
                    ]);
                } else {
                    Unit::create([
                        'kod' => $kod,
                        'nama' => $unitName,
                        'sektor_id' => $sektor->id,
                    ]);
                }
            }
        }

        echo "Units seeded: " . Unit::count() . " records\n";
    }
}