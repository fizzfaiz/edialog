<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PejabatPendidikan;

class PejabatPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $canonical = [
            'KPM' => [
                'nama' => 'Kementerian Pendidikan Malaysia',
                'jenis' => 'kpm',
                'induk_id' => null,
            ],
            'JPN-MELAKA' => [
                'nama' => 'Jabatan Pendidikan Negeri Melaka',
                'jenis' => 'jpn',
                'induk_id' => null,
            ],
            'PPD-AG' => [
                'nama' => 'PPD Alor Gajah',
                'jenis' => 'ppd',
            ],
            'PPD-JS' => [
                'nama' => 'PPD Jasin',
                'jenis' => 'ppd',
            ],
            'PPD-MT' => [
                'nama' => 'PPD Melaka Tengah',
                'jenis' => 'ppd',
            ],
        ];

        $jpn = null;
        foreach ($canonical as $kod => $data) {
            $record = PejabatPendidikan::where('kod', $kod)
                ->orWhere(function ($query) use ($data) {
                    $query->where('nama', $data['nama'])->where('jenis', $data['jenis']);
                })
                ->first();

            if (!$record) {
                $record = new PejabatPendidikan();
            }

            $record->kod = $kod;
            $record->nama = $data['nama'];
            $record->jenis = $data['jenis'];
            $record->induk_id = $data['induk_id'] ?? ($jpn ? $jpn->id : null);
            $record->save();

            if ($kod === 'JPN-MELAKA') {
                $jpn = $record;
            }
        }
    }
}