<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PejabatPendidikan;
use App\Models\Sektor;

class SektorPpdSeeder extends Seeder
{
    public function run(): void
    {
        $ppds = PejabatPendidikan::jenis('ppd')->get();

        if ($ppds->isEmpty()) {
            echo "TIADA PPD dijumpai. Sila jalankan TestUserSeeder dahulu.\n";
            return;
        }

        $sektorNama = [
            'SEKTOR PERANCANGAN',
            'SEKTOR PEMBELAJARAN',
            'SEKTOR PENGURUSAN SEKOLAH',
            'SEKTOR PEMBANGUNAN MURID',
            'SEKTOR PSIKOLOGI DAN KAUNSELING',
            'SEKTOR PENTAKSIRAN DAN PEPERIKSAAN',
            'SEKTOR PENGURUSAN',
            'UNIT SUMBER MANUSIA, ICT DAN PENTADBIRAN AM',
            'UNIT KEWANGAN, INFRASTRUKTUR DAN PEROLEHAN',
        ];

        foreach ($ppds as $ppd) {
            echo "--- {$ppd->nama} (ID: {$ppd->id}) ---\n";

            foreach ($sektorNama as $index => $nama) {
                $kod = strtoupper(substr(str_replace(' ', '', $ppd->nama), 0, 5)) . '-S' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                $sektor = Sektor::firstOrCreate(
                    ['kod' => $kod],
                    [
                        'nama' => $nama,
                        'pejabat_pendidikan_id' => $ppd->id,
                    ]
                );

                echo ($sektor->wasRecentlyCreated ? '  CREATED' : '  EXISTS') . ": {$nama} (Kod: {$kod})\n";
            }
        }

        echo "Done! Total Sektor keseluruhan: " . Sektor::count() . "\n";
    }
}