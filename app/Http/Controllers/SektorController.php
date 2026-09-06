<?php

namespace App\Http\Controllers;

use App\Models\Sektor;
use App\Http\Controllers\Traits\ScopesUserRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SektorController extends Controller
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

        $sektors = Sektor::query()
            ->when(! $scope['full'], fn ($q) => $q->whereIn('pejabat_pendidikan_id', $scope['pejabat_ids']))
            ->with('pejabatPendidikan')
            ->withCount('units')
            ->ordered()
            ->get();

        return view('sektor.index', compact('sektors'));
    }

    public function create(): View
    {
        $pejabats = $this->availablePejabats($this->registrationScope());

        return view('sektor.create', compact('pejabats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => 'required|string|max:255|unique:sektors,kod',
            'pejabat_pendidikan_id' => 'required|integer|exists:pejabat_pendidikans,id',
        ]);

        $scope = $this->registrationScope();
        if (! $this->pejabatInScope($scope, $data['pejabat_pendidikan_id'])) {
            abort(403, 'Pejabat di luar skop anda.');
        }

        $data['sort_order'] = Sektor::where('pejabat_pendidikan_id', $data['pejabat_pendidikan_id'])->max('sort_order') + 1;

        Sektor::create($data);

        return redirect()->route('sektor.index')->withSuccess('Sektor berjaya ditambah.');
    }

    public function edit(Sektor $sektor): View
    {
        $this->authorizeSektor($sektor);

        $pejabats = $this->availablePejabats($this->registrationScope());

        return view('sektor.edit', compact('sektor', 'pejabats'));
    }

    public function update(Request $request, Sektor $sektor): RedirectResponse
    {
        $this->authorizeSektor($sektor);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => 'required|string|max:255|unique:sektors,kod,'.$sektor->id,
            'pejabat_pendidikan_id' => 'required|integer|exists:pejabat_pendidikans,id',
        ]);

        $scope = $this->registrationScope();
        if (! $this->pejabatInScope($scope, $data['pejabat_pendidikan_id'])) {
            abort(403, 'Pejabat di luar skop anda.');
        }

        $sektor->update($data);

        return redirect()->route('sektor.index')->withSuccess('Sektor berjaya dikemas kini.');
    }

    public function destroy(Sektor $sektor): RedirectResponse
    {
        $this->authorizeSektor($sektor);

        if ($sektor->units()->exists()) {
            return redirect()->route('sektor.index')->with('error', 'Tidak boleh padam: sektor masih mempunyai unit.');
        }

        $sektor->delete();

        return redirect()->route('sektor.index')->withSuccess('Sektor berjaya dipadam.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $ids = (array) $request->input('order', []);
        $scope = $this->registrationScope();

        foreach ($ids as $index => $id) {
            $sektor = Sektor::find($id);
            if (! $sektor) {
                continue;
            }
            if (! $scope['full'] && ! in_array((int) $sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true)) {
                continue;
            }
            $sektor->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function authorizeSektor(Sektor $sektor): void
    {
        $scope = $this->registrationScope();
        if (! $scope['full'] && ! $this->pejabatInScope($scope, $sektor->pejabat_pendidikan_id)) {
            abort(403, 'Sektor di luar skop anda.');
        }
    }
}
