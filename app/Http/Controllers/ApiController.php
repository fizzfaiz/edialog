<?php

namespace App\Http\Controllers;

use App\Models\Sektor;
use App\Models\Unit;
use App\Models\PejabatPendidikan;
use App\Http\Controllers\Traits\ScopesUserRegistration;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    use ScopesUserRegistration;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sektorsByPejabat($pejabatId): JsonResponse
    {
        $scope = $this->registrationScope();
        if (!$this->pejabatInScope($scope, $pejabatId)) {
            abort(403);
        }

        $pejabatIds = [(int) $pejabatId];
        $pejabat = PejabatPendidikan::find($pejabatId);
        if ($pejabat && $pejabat->isJpn()) {
            $pejabatIds = array_merge($pejabatIds, $pejabat->anak()->pluck('id')->map(fn ($id) => (int) $id)->all());
        }

        $sektors = Sektor::whereIn('pejabat_pendidikan_id', $pejabatIds)
            ->select('id', 'nama', 'kod')
            ->orderBy('nama')
            ->get();

        return response()->json($sektors);
    }

    public function unitsBySektor($sektorId): JsonResponse
    {
        $scope = $this->registrationScope();
        if (!$this->sektorInScope($scope, $sektorId)) {
            abort(403);
        }

        $units = Unit::where('sektor_id', $sektorId)
            ->select('id', 'nama', 'kod')
            ->orderBy('nama')
            ->get();

        return response()->json($units);
    }
}