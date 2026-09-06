<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PejabatPendidikan;
use App\Models\Sektor;

class SektorJpnSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create JPN Melaka
        $jpn = PejabatPendidikan::firstOrCreate(
            ['kod' => 'JPNMLK'],
            ['nama' => 'JPN Melaka', 'jenis' => 'jpn']
        );

        echo "JPN: ID={$jpn->id} - {$jpn->nama}\n";

        $sektors = [
            'PENGARAH PENDIDIKAN NEGERI',
            'SEKTOR PERANCANGAN DAN PENGURUSAN PPD',
            'SEKTOR PENGURUSAN SEKOLAH',
            'SEKTOR PEMBELAJARAN',
            'SEKTOR PEMBANGUNAN MURID',
            'SEKTOR PENDIDIKAN ISLAM',
            'SEKTOR PENDIDIKAN KHAS',
            'SEKTOR INFRASTRUKTUR DAN PEROLEHAN',
            'SEKTOR SUMBER MANUSIA',
            'SEKTOR PENGURUSAN',
            'SEKTOR PENTAKSIRAN DAN PEPERIKSAAN',
            'SEKTOR SUMBER TEKNOLOGI PENDIDIKAN',
            'SEKTOR PENGURUSAN MAKLUMAT',
            'SEKTOR PSIKOLOGI DAN KAUNSELING',
            'SEKTOR INTEGRITI',
        ];

        foreach ($sektors as $index => $nama) {
            $kod = 'JPN-S' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            $sektor = Sektor::firstOrCreate(
                ['kod' => $kod],
                [
                    'nama' => $nama,
                    'pejabat_pendidikan_id' => $jpn->id,
                ]
            );

            echo ($sektor->wasRecentlyCreated ? 'CREATED' : 'EXISTS') . ": {$sektor->nama} (Kod: {$kod})\n";
        }

        echo "Done! Total Sektor JPN: " . Sektor::where('pejabat_pendidikan_id', $jpn->id)->count() . "\n";
    }
}