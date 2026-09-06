<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DialogPrestasiIssue;
use App\Models\DialogPrestasiReport;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kategoriList = [
            'Dialog Prestasi Negeri'           => 'bi-building',
            'Dialog Prestasi Berfokus Negeri'  => 'bi-bullseye',
            'Dialog Prestasi Berfokus Daerah'  => 'bi-geo-alt',
            'Dialog Prestasi PPD'              => 'bi-diagram-3',
            'Dialog Prestasi Daerah'           => 'bi-map',
            'Dialog Prestasi Mingguan Daerah'  => 'bi-calendar-week',
        ];

        $dashboardData = [];

        foreach ($kategoriList as $kategori => $icon) {
            $baseQuery = DialogPrestasiIssue::whereHas('report', function ($q) use ($kategori) {
                $q->where('kategori', $kategori);
            });

            $selesai       = (clone $baseQuery)->where('status', 'selesai')->count();
            $dalamProgress = (clone $baseQuery)->where('status', 'dalam_progress')->count();
            $belumSelesai  = (clone $baseQuery)->where('status', 'belum_selesai')->count();
            $jumlah        = $selesai + $dalamProgress + $belumSelesai;

            $dashboardData[] = [
                'kategori'      => $kategori,
                'icon'          => $icon,
                'jumlah'        => $jumlah,
                'selesai'       => $selesai,
                'dalam_progress'=> $dalamProgress,
                'belum_selesai' => $belumSelesai,
            ];
        }

        return view('home', compact('dashboardData'));
    }

}
