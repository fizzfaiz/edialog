<?php

namespace App\Http\Controllers;

use App\Models\DialogPrestasiReport;
use App\Models\PejabatPendidikan;
use App\Models\Sektor;
use App\Http\Requests\StoreDialogPrestasiReportRequest;
use App\Http\Requests\UpdateDialogPrestasiReportRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DialogPrestasiReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-dialog-prestasi|create-dialog-prestasi|edit-dialog-prestasi|delete-dialog-prestasi', ['only' => ['index','show','data']]);
        $this->middleware('permission:create-dialog-prestasi|edit-dialog-prestasi', ['only' => ['autosave']]);
        $this->middleware('permission:create-dialog-prestasi', ['only' => ['create','store']]);
        $this->middleware('permission:edit-dialog-prestasi', ['only' => ['edit','update']]);
        $this->middleware('permission:feedback-dialog-prestasi', ['only' => ['feedback','feedbackAutosave']]);
        $this->middleware('permission:delete-dialog-prestasi', ['only' => ['destroy']]);
    }

    private function applyScope($query): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('Super Admin')) return;
        $office = $user?->pejabatPendidikan;
        if (!$office) return;
        if ($office->isKpm()) return;
        if ($office->isJpn()) {
            $ppdIds = $office->anak()->pluck('id')->toArray();
            $ppdIds[] = $office->id;
            $query->whereIn('pejabat_pendidikan_id', $ppdIds);
            $this->applySektorUnitScope($query, $user);
            return;
        }
        if ($office->isPpd()) {
            $query->where('pejabat_pendidikan_id', $office->id);
            $this->applySektorUnitScope($query, $user);
            return;
        }
    }

    private function applySektorUnitScope($query, $user): void
    {
        if ($user->unit_id) { $query->where(fn($q) => $q->where('unit_id', $user->unit_id)->orWhereNull('unit_id')); }
        elseif ($user->sektor_id) { $query->where(fn($q) => $q->where('sektor_id', $user->sektor_id)->orWhereNull('sektor_id')); }
    }

    public function index(Request $request): View
    {
        $query = DialogPrestasiReport::query();
        $this->applyScope($query);
        if ($request->filled('tarikh')) { $query->whereDate('tarikh', $request->tarikh); }
        $reports = $query->orderBy('tarikh', 'desc')->paginate(10)->withQueryString();
        $pejabats = [];
        $user = auth()->user();
        if ($user) {
            $office = $user->pejabatPendidikan;
            if ($office && $office->isKpm()) { $pejabats = PejabatPendidikan::jenis('ppd')->get(); }
            elseif ($office && $office->isJpn()) { $pejabats = $office->anak()->get(); }
        }
        return view('dialog_prestasi.index', compact('reports', 'pejabats'));
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $query = DialogPrestasiReport::query();
            $this->applyScope($query);
            $recordsTotal = $query->count();
            if ($request->filled('tarikh')) { $query->whereDate('tarikh', $request->tarikh); }
            if ($request->filled('search') && $request->input('search.value')) {
                $search = $request->input('search.value');
                $query->where(fn($q) => $q->where('pengerusi','like',"%{$search}%")->orWhere('hari','like',"%{$search}%")->orWhere('tempat','like',"%{$search}%"));
            }
            $recordsFiltered = $query->count();
            $orderColumn = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'desc');
            $columns = ['id', 'pengerusi', 'tarikh', 'hari', 'masa', 'tempat'];
            if (isset($columns[$orderColumn])) { $query->orderBy($columns[$orderColumn], $orderDir); }
            else { $query->orderBy('tarikh', 'desc'); }
            $start = intval($request->input('start', 0));
            $length = intval($request->input('length', 10));
            $reports = $query->skip($start)->take($length)->get();
            $data = $reports->map(function ($report, $index) use ($start) {
                $showUrl = route('dialog-prestasi.show', $report->id);
                $editUrl = route('dialog-prestasi.edit', $report->id);
                $printUrl = route('dialog-prestasi.print', $report->id);
                $deleteUrl = route('dialog-prestasi.destroy', $report->id);
                $ft = '-'; try { $ft = $report->tarikh instanceof \Carbon\Carbon ? $report->tarikh->format('d/m/Y') : date('d/m/Y', strtotime($report->tarikh)); } catch (\Exception $e) { $ft = $report->tarikh; }
                $fm = '-'; try { $fm = $report->masa instanceof \Carbon\Carbon ? $report->masa->format('H:i') : substr($report->masa, 0, 5); } catch (\Exception $e) { $fm = $report->masa; }
                $u = auth()->user();
                $h = '<div class="d-flex gap-1 flex-wrap">';
                $h .= '<a href="'.$showUrl.'" class="btn btn-warning btn-sm" title="Lihat"><i class="bi bi-eye"></i></a>';
                if ($u && $u->can('edit-dialog-prestasi')) $h .= '<a href="'.$editUrl.'" class="btn btn-primary btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>';
                if ($u && $u->can('feedback-dialog-prestasi')) { $fu = route('dialog-prestasi.feedback', $report->id); $h .= '<a href="'.$fu.'" class="btn btn-info btn-sm" title="Maklum Balas"><i class="bi bi-chat-dots"></i></a>'; }
                $h .= '<a href="'.$printUrl.'" class="btn btn-danger btn-sm" target="_blank" title="PDF"><i class="bi bi-file-earmark-pdf"></i></a>';
                if ($u && $u->can('delete-dialog-prestasi')) $h .= '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$report->id.'" data-url="'.$deleteUrl.'" title="Padam"><i class="bi bi-trash"></i></button>';
                $h .= '</div>';
                return ['DT_RowIndex' => $start + $index + 1, 'pengerusi' => $report->pengerusi ?? '-', 'tarikh' => $ft, 'hari' => $report->hari ?? '-', 'masa' => $fm, 'tempat' => $report->tempat ?? '-', 'action' => $h];
            });
            return response()->json(['draw' => intval($request->input('draw', 1)), 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error('DataTables Dialog Prestasi error: ' . $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return response()->json(['draw' => intval($request->input('draw', 1)), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => 'Internal server error.'], 500);
        }
    }

    public function create(): View
    {
        $user = auth()->user();
        $office = $user?->pejabatPendidikan;
        $sektors = collect();
        if ($office) {
            if ($office->isPpd()) { $sektors = Sektor::where('pejabat_pendidikan_id', $office->id)->get(); }
            elseif ($office->isJpn()) { $ppdIds = $office->anak()->pluck('id')->toArray(); $ppdIds[] = $office->id; $sektors = Sektor::whereIn('pejabat_pendidikan_id', $ppdIds)->get(); }
            else { $sektors = Sektor::all(); }
        }
        $unitsMap = [];
        foreach ($sektors as $s) { $unitsMap[$s->id] = $s->units()->select('id', 'nama', 'sektor_id')->get(); }
        return view('dialog_prestasi.create', compact('sektors', 'unitsMap', 'office', 'user'));
    }

    public function store(StoreDialogPrestasiReportRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = auth()->user();
        $office = $user?->pejabatPendidikan;
        if (empty($data['pejabat_pendidikan_id']) && $office && $office->isPpd()) $data['pejabat_pendidikan_id'] = $office->id;
        if (empty($data['sektor_id']) && $user->sektor_id) $data['sektor_id'] = $user->sektor_id;
        if (empty($data['unit_id']) && $user->unit_id) $data['unit_id'] = $user->unit_id;
        if (empty($data['dicatat_oleh']) && $user) $data['dicatat_oleh'] = $user->name;
        $report = DialogPrestasiReport::create($data);
        if ($request->has('attendances')) { foreach ($request->attendances as $a) { if (!empty($a['nama']) || !empty($a['jawatan'])) $report->attendances()->create(['nama' => $a['nama'] ?? '', 'jawatan' => $a['jawatan'] ?? '']); } }
        if ($request->has('issues')) { foreach ($request->issues as $i => $v) { $v['bil'] = $i + 1; $v['sektor_pegawai'] = $v['sektor_pegawai'] ?? ''; $report->issues()->create($v); } }
        return redirect()->route('dialog-prestasi.index')->withSuccess('Laporan Dialog Prestasi berjaya disimpan.');
    }

    private function authorizeAccess(DialogPrestasiReport $report): void
    {
        $user = auth()->user();
        if ($user && $user->hasRole('Super Admin')) return;
        $office = $user?->pejabatPendidikan;
        if (!$office) abort(403, 'ANDA TIDAK MEMPUNYAI PEJABAT PENDIDIKAN.');
        if ($office->isKpm()) return;
        if ($office->isJpn()) {
            $ppdIds = $office->anak()->pluck('id')->toArray();
            $ppdIds[] = $office->id;
            if (!in_array((int) $report->pejabat_pendidikan_id, array_map('intval', $ppdIds), true)) abort(403, 'ANDA TIDAK MEMPUNYAI CAPAIAN.');
            return;
        }
        if ($office->isPpd()) { if ($report->pejabat_pendidikan_id !== $office->id) abort(403, 'ANDA TIDAK MEMPUNYAI CAPAIAN.'); return; }
        abort(403, 'ANDA TIDAK MEMPUNYAI CAPAIAN.');
    }

    public function show(DialogPrestasiReport $dialogPrestasiReport): View { $this->authorizeAccess($dialogPrestasiReport); $dialogPrestasiReport->load('issues', 'attendances'); return view('dialog_prestasi.show', ['dialogPrestasiReport' => $dialogPrestasiReport]); }

    public function edit(DialogPrestasiReport $dialogPrestasiReport): View
    {
        $this->authorizeAccess($dialogPrestasiReport);
        $dialogPrestasiReport->load('issues', 'attendances');
        $user = auth()->user();
        $office = $user?->pejabatPendidikan;
        $sektors = collect();
        if ($office) {
            if ($office->isPpd()) { $sektors = Sektor::where('pejabat_pendidikan_id', $office->id)->get(); }
            elseif ($office->isJpn()) { $ppdIds = $office->anak()->pluck('id')->toArray(); $ppdIds[] = $office->id; $sektors = Sektor::whereIn('pejabat_pendidikan_id', $ppdIds)->get(); }
            else { $sektors = Sektor::all(); }
        }
        $unitsMap = [];
        foreach ($sektors as $s) { $unitsMap[$s->id] = $s->units()->select('id', 'nama', 'sektor_id')->get(); }
        return view('dialog_prestasi.edit', compact('dialogPrestasiReport', 'sektors', 'unitsMap'));
    }

    public function update(UpdateDialogPrestasiReportRequest $request, DialogPrestasiReport $dialogPrestasiReport): RedirectResponse
    {
        $this->authorizeAccess($dialogPrestasiReport);
        $dialogPrestasiReport->update($request->validated());
        $dialogPrestasiReport->attendances()->delete(); if ($request->has('attendances')) foreach ($request->attendances as $a) { if (!empty($a['nama']) || !empty($a['jawatan'])) $dialogPrestasiReport->attendances()->create(['nama' => $a['nama'] ?? '', 'jawatan' => $a['jawatan'] ?? '']); }
        $dialogPrestasiReport->issues()->delete(); if ($request->has('issues')) foreach ($request->issues as $i => $v) { $v['bil'] = $i + 1; $v['sektor_pegawai'] = $v['sektor_pegawai'] ?? ''; $dialogPrestasiReport->issues()->create($v); }
        return redirect()->route('dialog-prestasi.index')->withSuccess('Laporan dikemaskini.');
    }

    public function destroy(DialogPrestasiReport $dialogPrestasiReport): RedirectResponse
    {
        $this->authorizeAccess($dialogPrestasiReport);
        $u = auth()->user(); $o = $u?->pejabatPendidikan;
        if (!$u?->hasRole('Super Admin') && (!$o || !$o->isKpm())) abort(403, 'HANYA KPM BOLEH PADAM.');
        $dialogPrestasiReport->delete();
        return redirect()->route('dialog-prestasi.index')->withSuccess('Laporan dipadam.');
    }

    public function bulkDestroy(Request $r): RedirectResponse
    {
        $ids = $r->input('ids', []); if (empty($ids)) return redirect()->route('dialog-prestasi.index')->with('error', 'Tiada laporan dipilih.');
        $u = auth()->user(); $o = $u?->pejabatPendidikan;
        if (!$u?->hasRole('Super Admin') && (!$o || !$o->isKpm())) abort(403, 'HANYA KPM BOLEH PADAM.');
        DialogPrestasiReport::whereIn('id', $ids)->delete();
        return redirect()->route('dialog-prestasi.index')->withSuccess(count($ids) . ' laporan dipadam.');
    }

    public function printPdf(DialogPrestasiReport $dialogPrestasiReport) { $this->authorizeAccess($dialogPrestasiReport); $dialogPrestasiReport->load('issues.taggedSektor', 'issues.taggedUnit', 'attendances'); return Pdf::loadView('dialog_prestasi.pdf', ['dialogPrestasiReport' => $dialogPrestasiReport])->download('laporan-'.$dialogPrestasiReport->id.'.pdf'); }
    public function feedback(DialogPrestasiReport $dialogPrestasiReport): View { $this->authorizeAccess($dialogPrestasiReport); $dialogPrestasiReport->load('issues.taggedSektor', 'issues.taggedUnit', 'attendances'); return view('dialog_prestasi.feedback', ['dialogPrestasiReport' => $dialogPrestasiReport]); }

    public function feedbackAutosave(Request $r, DialogPrestasiReport $d): JsonResponse
    {
        $this->authorizeAccess($d);
        $r->validate(['issues' => 'nullable|array', 'issues.*.id' => 'required|integer|exists:dialog_prestasi_issues,id', 'issues.*.jawapan' => 'nullable|string', 'issues.*.status' => 'nullable|string|max:50']);
        $u = auth()->user();
        if ($r->has('issues')) foreach ($r->issues as $v) {
            if (isset($v['id']) && !empty($v['id'])) {
                $issue = $d->issues()->find($v['id']); if (!$issue) continue;
                if (!$this->canEditFeedback($issue, $u)) continue;
                $issue->update(['jawapan' => $v['jawapan'] ?? $issue->jawapan, 'status' => $v['status'] ?? $issue->status, 'answered_by' => $u?->id]);
            }
        }
        return response()->json(['success' => true, 'message' => 'Maklum balas disimpan.']);
    }

    private function canEditFeedback($i, $u): bool
    {
        if (!$u) return false;
        if ($u->hasRole('Super Admin')) return true;

        $o = $u->pejabatPendidikan;
        if (!$o) return false;

        if ($o->isKpm()) return true;

        if ($o->isJpn()) {
            $reportPejabatId = $i->report?->pejabat_pendidikan_id;
            $ppdIds = $o->anak()->pluck('id')->toArray();
            $ppdIds[] = $o->id;
            if ($reportPejabatId && in_array((int) $reportPejabatId, array_map('intval', $ppdIds), true)) {
                return true;
            }
        }

        if ($o->isPpd()) {
            $reportPejabatId = $i->report?->pejabat_pendidikan_id;
            if ($reportPejabatId && (int) $reportPejabatId === (int) $o->id) {
                return true;
            }
        }

        if ($i->tagged_sektor_id && (int) $u->sektor_id === (int) $i->tagged_sektor_id) return true;
        if ($i->tagged_unit_id && (int) $u->unit_id === (int) $i->tagged_unit_id) return true;
        return false;
    }

    public function autosave(Request $r)
    {
        $data = $r->validate([
            'report_id' => 'nullable|integer|exists:dialog_prestasi_reports,id',
            'kategori' => 'nullable|string|max:255', 'pengerusi' => 'nullable|string|max:255', 'tarikh' => 'nullable|date',
            'hari' => 'nullable|string|max:50', 'masa' => 'nullable', 'tempat' => 'nullable|string|max:255',
            'dicatat_oleh' => 'nullable|string|max:255', 'jawatan_pencatat' => 'nullable|string|max:255',
            'disahkan_oleh' => 'nullable|string|max:255', 'jawatan_pengesah' => 'nullable|string|max:255',
            'attendances' => 'nullable|array', 'attendances.*.nama' => 'nullable|string|max:255', 'attendances.*.jawatan' => 'nullable|string|max:255',
            'issues' => 'nullable|array', 'issues.*.isu' => 'nullable|string', 'issues.*.fokus' => 'nullable|string', 'issues.*.tindakan' => 'nullable|string', 'issues.*.sektor_pegawai' => 'nullable|string|max:255',
            'issues.*.tagged_sektor_id' => 'nullable|integer', 'issues.*.tagged_unit_id' => 'nullable|integer',
        ]);

        if (!empty($data['report_id'])) {
            $report = DialogPrestasiReport::findOrFail($data['report_id']);
            $report->update([ 'kategori' => $data['kategori'] ?? $report->kategori, 'pengerusi' => $data['pengerusi'] ?? $report->pengerusi,
                'tarikh' => !empty($data['tarikh']) ? $data['tarikh'] : $report->tarikh, 'hari' => $data['hari'] ?? $report->hari,
                'masa' => !empty($data['masa']) ? $data['masa'] : $report->masa, 'tempat' => $data['tempat'] ?? $report->tempat,
                'dicatat_oleh' => $data['dicatat_oleh'] ?? $report->dicatat_oleh, 'jawatan_pencatat' => $data['jawatan_pencatat'] ?? $report->jawatan_pencatat,
                'disahkan_oleh' => $data['disahkan_oleh'] ?? $report->disahkan_oleh, 'jawatan_pengesah' => $data['jawatan_pengesah'] ?? $report->jawatan_pengesah ]);
        } else {
            if (empty($data['tarikh'])) return response()->json(['success' => false, 'message' => 'Sila isi tarikh.'], 422);
            $u = auth()->user(); $o = $u?->pejabatPendidikan;
            $rd = [ 'kategori' => $data['kategori'] ?? '', 'pengerusi' => $data['pengerusi'] ?? '', 'tarikh' => $data['tarikh'],
                'hari' => $data['hari'] ?? '', 'masa' => !empty($data['masa']) ? $data['masa'] : '00:00', 'tempat' => $data['tempat'] ?? '',
                'dicatat_oleh' => $data['dicatat_oleh'] ?? $u?->name ?? '', 'jawatan_pencatat' => $data['jawatan_pencatat'] ?? '',
                'disahkan_oleh' => $data['disahkan_oleh'] ?? '', 'jawatan_pengesah' => $data['jawatan_pengesah'] ?? '' ];
            if ($o && $o->isPpd()) $rd['pejabat_pendidikan_id'] = $o->id;
            if (!empty($u->sektor_id)) $rd['sektor_id'] = $u->sektor_id;
            if (!empty($u->unit_id)) $rd['unit_id'] = $u->unit_id;
            $report = DialogPrestasiReport::create($rd);
        }

        if (isset($data['attendances'])) { $report->attendances()->delete(); foreach ($data['attendances'] as $a) { if (!empty($a['nama']) || !empty($a['jawatan'])) $report->attendances()->create(['nama' => $a['nama'] ?? '', 'jawatan' => $a['jawatan'] ?? '']); } }
        if (isset($data['issues'])) { $report->issues()->delete(); foreach ($data['issues'] as $i => $v) { if (!empty($v['isu']) || !empty($v['fokus']) || !empty($v['tindakan']) || !empty($v['sektor_pegawai'])) { $v['bil'] = $i + 1; $v['isu'] = $v['isu'] ?? ''; $v['fokus'] = $v['fokus'] ?? ''; $v['tindakan'] = $v['tindakan'] ?? ''; $v['sektor_pegawai'] = $v['sektor_pegawai'] ?? ''; $report->issues()->create($v); } } }
        return response()->json(['success' => true, 'report_id' => $report->id, 'message' => 'Data disimpan.']);
    }
}