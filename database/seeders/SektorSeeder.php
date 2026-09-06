<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sektor;
use App\Models\PejabatPendidikan;

class SektorSeeder extends Seeder
{
    public function run(): void
    {
        $ppds = PejabatPendidikan::where('jenis', 'ppd')->get();

        $sektorNames = [
            'Sektor Akademik',
            'Sektor Pembangunan Murid',
            'Sektor Pengurusan Sekolah',
            'Sektor Perancangan dan Pengurusan',
            'Sektor Teknologi Maklumat dan Komunikasi',
            'Sektor Pendidikan Islam',
            'Sektor Sumber Manusia',
            'Sektor Kewangan dan Akaun',
        ];

        foreach ($ppds as $ppd) {
            foreach ($sektorNames as $index => $name) {
                $kod = strtoupper(str_replace(['-', ' ', '&', '/', '.'], '', $ppd->kod)) . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                $sektor = Sektor::where('kod', $kod)->first();
                if (!$sektor) {
                    $sektor = Sektor::where('nama', $name)
                        ->where('pejabat_pendidikan_id', $ppd->id)
                        ->first();
                }

                if ($sektor) {
                    $sektor->update([
                        'kod' => $kod,
                        'nama' => $name,
                        'pejabat_pendidikan_id' => $ppd->id,
                    ]);
                } else {
                    Sektor::create([
                        'kod' => $kod,
                        'nama' => $name,
                        'pejabat_pendidikan_id' => $ppd->id,
                    ]);
                }
            }
        }

        echo "Sektor seeded: " . Sektor::count() . " records\n";
    }
}