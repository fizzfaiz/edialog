<?php

namespace App\Http\Controllers;

use App\Models\Sektor;
use App\Models\Unit;
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

        $sektors = Sektor::where('pejabat_pendidikan_id', $pejabatId)
            ->select('id', 'nama', 'kod')
            ->orderBy('nama')
            ->get();

        return response()->json($sektors);
    }

    public function unitsBySektor($sektorId): JsonResponse
    {
        $scope = $this->registrationScope();
        $sektor = Sektor::find($sektorId);
        if (! $sektor || ! $this->pejabatInScope($scope, $sektor->pejabat_pendidikan_id)) {
            abort(403);
        }

        $units = Unit::where('sektor_id', $sektorId)
            ->select('id', 'nama', 'kod')
            ->orderBy('nama')
            ->get();

        return response()->json($units);
    }
}