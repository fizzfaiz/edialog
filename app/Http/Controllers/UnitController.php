<?php

namespace App\Http\Controllers;

use App\Models\Sektor;
use App\Models\Unit;
use App\Http\Controllers\Traits\ScopesUserRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnitController extends Controller
{
    use ScopesUserRegistration;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-sektor-unit');
    }

    public function index(): View
    {
        $scope = $this->registrationScope();

        $units = Unit::query()
            ->whereHas('sektor', function ($q) use ($scope) {
                if (! $scope['full']) {
                    $q->whereIn('pejabat_pendidikan_id', $scope['pejabat_ids']);
                }
            })
            ->with('sektor.pejabatPendidikan')
            ->ordered()
            ->get();

        return view('unit.index', compact('units'));
    }

    public function create(): View
    {
        $sektors = $this->availableSektors();

        return view('unit.create', compact('sektors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => 'required|string|max:255|unique:units,kod',
            'sektor_id' => 'required|integer|exists:sektors,id',
        ]);

        $this->authorizeSektorId($data['sektor_id']);

        $data['sort_order'] = Unit::where('sektor_id', $data['sektor_id'])->max('sort_order') + 1;

        Unit::create($data);

        return redirect()->route('unit.index')->withSuccess('Unit berjaya ditambah.');
    }

    public function edit(Unit $unit): View
    {
        $this->authorizeUnit($unit);

        $sektors = $this->availableSektors();

        return view('unit.edit', compact('unit', 'sektors'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $this->authorizeUnit($unit);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => 'required|string|max:255|unique:units,kod,'.$unit->id,
            'sektor_id' => 'required|integer|exists:sektors,id',
        ]);

        $this->authorizeSektorId($data['sektor_id']);

        $unit->update($data);

        return redirect()->route('unit.index')->withSuccess('Unit berjaya dikemas kini.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $this->authorizeUnit($unit);

        if ($unit->users()->exists()) {
            return redirect()->route('unit.index')->with('error', 'Tidak boleh padam: unit sedang digunakan oleh pengguna.');
        }

        $unit->delete();

        return redirect()->route('unit.index')->withSuccess('Unit berjaya dipadam.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $ids = (array) $request->input('order', []);
        $sektorId = $request->input('sektor_id');
        $scope = $this->registrationScope();

        if ($sektorId) {
            $this->authorizeSektorId($sektorId);
        }

        foreach ($ids as $index => $id) {
            $unit = Unit::find($id);
            if (! $unit || ! $unit->sektor) {
                continue;
            }
            if (! $scope['full'] && ! in_array((int) $unit->sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true)) {
                continue;
            }
            $data = ['sort_order' => $index];
            if ($sektorId) {
                $data['sektor_id'] = $sektorId;
            }
            $unit->update($data);
        }

        return response()->json(['success' => true]);
    }

    private function availableSektors()
    {
        $scope = $this->registrationScope();

        return Sektor::query()
            ->when(! $scope['full'], fn ($q) => $q->whereIn('pejabat_pendidikan_id', $scope['pejabat_ids']))
            ->with('pejabatPendidikan')
            ->ordered()
            ->get();
    }

    private function authorizeUnit(Unit $unit): void
    {
        $scope = $this->registrationScope();
        if (! $scope['full'] && (! $unit->sektor || ! in_array((int) $unit->sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true))) {
            abort(403, 'Unit di luar skop anda.');
        }
    }

    private function authorizeSektorId($sektorId): void
    {
        $scope = $this->registrationScope();
        $sektor = Sektor::find($sektorId);
        if (! $sektor) {
            abort(404);
        }
        if (! $scope['full'] && ! in_array((int) $sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true)) {
            abort(403, 'Sektor di luar skop anda.');
        }
    }
}
