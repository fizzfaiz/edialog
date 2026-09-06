@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Maklum Balas Laporan Dialog Prestasi
                </div>
                <div class="float-end">
                    <a href="{{ route('dialog-prestasi.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle"></i> Pengguna dari pejabat yang sama boleh mengedit maklum balas. Data disimpan secara automatik.
                    <span id="autosaveStatus" class="float-end fw-bold text-success">Sedia</span>
                </div>

                <input type="hidden" id="report_id" value="{{ $dialogPrestasiReport->id }}">

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Tarikh</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->tarikh ? $dialogPrestasiReport->tarikh->format('d/m/Y') : '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Hari</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->hari ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Masa</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->masa ? $dialogPrestasiReport->masa->format('H:i') : '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Tempat</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->tempat ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Kategori</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->kategori ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Pengerusi</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->pengerusi ?: '-' }}</div>
                </div>

                <h6 class="text-primary fw-bold mt-4">KEHADIRAN</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width:5%">Bil</th>
                                <th style="width:47%">Nama</th>
                                <th style="width:48%">Jawatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dialogPrestasiReport->attendances as $index => $attendance)
                                <tr>
                                    <td><input type="number" class="form-control" value="{{ $index + 1 }}" disabled></td>
                                    <td>{{ $attendance->nama }}</td>
                                    <td>{{ $attendance->jawatan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td><input type="number" class="form-control" value="1" disabled></td>
                                    <td><input type="text" class="form-control" disabled></td>
                                    <td><input type="text" class="form-control" disabled></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="text-primary fw-bold mt-4">ISU DAN TINDAKAN</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered" id="issuesTable">
                        <thead class="table-light">
                             <tr>
                                <th style="width:5%">Bil</th>
                                <th style="width:12%">Fokus</th>
                                <th style="width:12%">Isu</th>
                                <th style="width:14%">Tindakan</th>
                                <th style="width:12%">Sektor/Unit</th>
                                <th style="width:30%">Jawapan/Maklumbalas</th>
                                <th style="width:15%">Status</th>
                            </tr>
                        </thead>
                        <tbody id="issuesBody">
                            @php
                                $currentUser = auth()->user();
                                $currentOffice = $currentUser?->pejabatPendidikan;
                                $isKpmJpn = $currentOffice && ($currentOffice->isKpm() || $currentOffice->isJpn());
                                $isSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');
                            @endphp
                            @forelse ($dialogPrestasiReport->issues as $index => $issue)
                                @php
                                    $issueReportPejabatId = $issue->report?->pejabat_pendidikan_id ?? $dialogPrestasiReport->pejabat_pendidikan_id;

                                    $canEdit = $isSuperAdmin
                                        || ($currentOffice && $currentOffice->isKpm())
                                        || ($currentOffice && $currentOffice->isJpn() && $issueReportPejabatId && in_array((int) $issueReportPejabatId, array_map('intval', $currentOffice->anak()->pluck('id')->all()), true))
                                        || ($currentOffice && $currentOffice->isPpd() && $issueReportPejabatId && (int) $issueReportPejabatId === (int) $currentOffice->id)
                                        || ($issue->tagged_sektor_id && $currentUser?->sektor_id == $issue->tagged_sektor_id)
                                        || ($issue->tagged_unit_id && $currentUser?->unit_id == $issue->tagged_unit_id);

                                    // Build answered-by metadata
                                    $answeredMeta = '';
                                    if ($issue->answeredBy) {
                                        $answerer = $issue->answeredBy;
                                        $answererInfo = $answerer->name;
                                        if ($answerer->pejabatPendidikan) {
                                            $answererInfo .= ' (' . $answerer->pejabatPendidikan->nama . ')';
                                        }
                                        $answeredMeta = $answererInfo . ' — ' . $issue->updated_at->format('d/m/Y h:i A');
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center"><span class="fw-bold">{{ $index + 1 }}</span></td>
                                    <td>{{ $issue->fokus }}</td>
                                    <td>{{ $issue->isu }}</td>
                                    <td>{{ $issue->tindakan }}</td>
                                    <td>
                                        <div class="fw-bold small">{{ $issue->taggedSektor?->nama ?? '-' }}</div>
                                        <div class="text-muted small">{{ $issue->taggedUnit?->nama ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <textarea name="jawapan[{{ $issue->id }}]"
                                            class="form-control autosave-field {{ $canEdit ? '' : 'bg-light' }}"
                                            rows="3" data-issue-id="{{ $issue->id }}"
                                            {{ $canEdit ? '' : 'disabled' }}
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}">{{ $issue->jawapan }}</textarea>
                                        @if($answeredMeta)
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-person-check"></i> {{ $answeredMeta }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-select autosave-field status-dropdown {{ $canEdit ? '' : 'bg-light' }}"
                                            data-issue-id="{{ $issue->id }}" style="min-width:130px;"
                                            {{ $canEdit ? '' : 'disabled' }}
                                            data-can-edit="{{ $canEdit ? '1' : '0' }}">
                                            <option value="" {{ $issue->status == '' ? 'selected' : '' }}>-- Pilih --</option>
                                            <option value="selesai" {{ $issue->status == 'selesai' ? 'selected' : '' }} class="bg-success text-white">Selesai</option>
                                            <option value="dalam_progress" {{ $issue->status == 'dalam_progress' ? 'selected' : '' }} class="bg-warning text-dark">Dalam Progress</option>
                                            <option value="belum_selesai" {{ $issue->status == 'belum_selesai' ? 'selected' : '' }} class="bg-danger text-white">Belum Selesai</option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center"><span class="fw-bold">1</span></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><textarea class="form-control autosave-field" rows="3"></textarea></td>
                                    <td>
                                        <select class="form-select autosave-field status-dropdown" style="min-width:130px;">
                                            <option value="">-- Pilih --</option>
                                            <option value="selesai" class="bg-success text-white">Selesai</option>
                                            <option value="dalam_progress" class="bg-warning text-dark">Dalam Progress</option>
                                            <option value="belum_selesai" class="bg-danger text-white">Belum Selesai</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="text-primary fw-bold mt-4">PENGESAHAN</h6>
                <hr>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Dicatat Oleh</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->dicatat_oleh ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Jawatan Pencatat</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->jawatan_pencatat ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Disahkan Oleh</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->disahkan_oleh ?: '-' }}</div>
                </div>

                <div class="mb-2 row">
                    <div class="col-md-4 text-md-end text-start fw-bold">Jawatan Pengesah</div>
                    <div class="col-md-6">{{ $dialogPrestasiReport->jawatan_pengesah ?: '-' }}</div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let autosaveTimer = null;
    let isSaving = false;
    let autosaveQueued = false;

    // Apply color to status dropdown based on selection
    function applyStatusColor(select) {
        if (select.disabled) return;
        select.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'text-white', 'text-dark');
        if (select.value === 'selesai') {
            select.classList.add('bg-success', 'text-white');
        } else if (select.value === 'dalam_progress') {
            select.classList.add('bg-warning', 'text-dark');
        } else if (select.value === 'belum_selesai') {
            select.classList.add('bg-danger', 'text-white');
        }
    }

    // Initialize all status dropdowns on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-dropdown').forEach(function(select) {
            applyStatusColor(select);
        });
    });

    // Autosave function
    function autosave(delay = 1000) {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(function() {
            performAutosave();
        }, delay);
    }

    function performAutosave() {
        if (isSaving) {
            autosaveQueued = true;
            return;
        }

        const statusEl = document.getElementById('autosaveStatus');
        statusEl.textContent = 'Menyimpan...';
        statusEl.className = 'float-end fw-bold text-warning';

        // Track processed issue IDs to avoid duplicates
        const processedIds = new Set();
        const issues = [];

        // Collect data from textareas (only those that can be edited)
        document.querySelectorAll('#issuesBody textarea.autosave-field').forEach(function(textarea) {
            const canEdit = textarea.getAttribute('data-can-edit') === '1';
            if (!canEdit) return;

            const issueId = textarea.getAttribute('data-issue-id');
            if (issueId && !processedIds.has(issueId)) {
                processedIds.add(issueId);
                const statusSelect = document.querySelector('.status-dropdown[data-issue-id="' + issueId + '"]');
                issues.push({
                    id: issueId,
                    jawapan: textarea.value,
                    status: statusSelect ? statusSelect.value : ''
                });
            }
        });

        // Also collect from status dropdowns
        document.querySelectorAll('#issuesBody select.status-dropdown').forEach(function(select) {
            const canEdit = select.getAttribute('data-can-edit') === '1';
            if (!canEdit) return;

            const issueId = select.getAttribute('data-issue-id');
            if (issueId && !processedIds.has(issueId)) {
                processedIds.add(issueId);
                const textarea = document.querySelector('textarea[data-issue-id="' + issueId + '"]');
                issues.push({
                    id: issueId,
                    jawapan: textarea ? textarea.value : '',
                    status: select.value
                });
            }
        });

        if (issues.length === 0) return;

        const data = { issues: issues };

        isSaving = true;

        fetch('{{ route("dialog-prestasi.feedback-autosave", $dialogPrestasiReport->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'HTTP Error ' + response.status);
                });
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                statusEl.textContent = 'Disimpan ' + new Date().toLocaleTimeString();
                statusEl.className = 'float-end fw-bold text-success';
                location.reload();
            } else {
                throw new Error(result.message || 'Gagal menyimpan');
            }
        })
        .catch(error => {
            console.error('Autosave error:', error);
            statusEl.textContent = 'Ralat: ' + (error.message || 'Ralat menyimpan');
            statusEl.className = 'float-end fw-bold text-danger';
        })
        .finally(() => {
            isSaving = false;
            if (autosaveQueued) {
                autosaveQueued = false;
                autosave(0);
            }
        });
    }

    // Listen for input changes on autosave fields (jawapan) - only if editable
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('autosave-field') && !e.target.disabled) {
            autosave();
        }
    });

    // Listen for change events on status dropdowns - only if editable
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('status-dropdown') && !e.target.disabled) {
            applyStatusColor(e.target);
            autosave();
        }
    });

    window.addEventListener('beforeunload', function() {
        if (autosaveTimer) {
            clearTimeout(autosaveTimer);
        }
    });
</script>
@endpush