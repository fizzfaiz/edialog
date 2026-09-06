@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Kemaskini Laporan Dialog Prestasi
                </div>
                <div class="float-end">
                    <a href="{{ route('dialog-prestasi.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle"></i> Borang ini disimpan secara automatik setiap kali data ditaip.
                    <span id="autosaveStatus" class="float-end fw-bold text-success">Sedia</span>
                </div>

                <input type="hidden" id="report_id" value="{{ $dialogPrestasiReport->id }}">

                <form action="{{ route('dialog-prestasi.update', $dialogPrestasiReport->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                <div class="mb-3 row">
                    <label for="tarikh" class="col-md-4 col-form-label text-md-end text-start">Tarikh</label>
                    <div class="col-md-6">
                        <input type="date" class="form-control autosave-field" id="tarikh" name="tarikh" value="{{ old('tarikh', $dialogPrestasiReport->tarikh->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="hari" class="col-md-4 col-form-label text-md-end text-start">Hari</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="hari" name="hari" value="{{ old('hari', $dialogPrestasiReport->hari) }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="masa" class="col-md-4 col-form-label text-md-end text-start">Masa</label>
                    <div class="col-md-6">
                        <input type="time" class="form-control autosave-field" id="masa" name="masa" value="{{ old('masa', $dialogPrestasiReport->masa->format('H:i')) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tempat" class="col-md-4 col-form-label text-md-end text-start">Tempat</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="tempat" name="tempat" value="{{ old('tempat', $dialogPrestasiReport->tempat) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="kategori" class="col-md-4 col-form-label text-md-end text-start">Kategori</label>
                    <div class="col-md-6">
                        <select class="form-select autosave-field" id="kategori" name="kategori">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Dialog Prestasi Negeri" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi Negeri' ? 'selected' : '' }}>Dialog Prestasi Negeri</option>
                            <option value="Dialog Prestasi Berfokus Negeri" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi Berfokus Negeri' ? 'selected' : '' }}>Dialog Prestasi Berfokus Negeri</option>
                            <option value="Dialog Prestasi Berfokus Daerah" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi Berfokus Daerah' ? 'selected' : '' }}>Dialog Prestasi Berfokus Daerah</option>
                            <option value="Dialog Prestasi PPD" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi PPD' ? 'selected' : '' }}>Dialog Prestasi PPD</option>
                            <option value="Dialog Prestasi Daerah" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi Daerah' ? 'selected' : '' }}>Dialog Prestasi Daerah</option>
                            <option value="Dialog Prestasi Mingguan Daerah" {{ old('kategori', $dialogPrestasiReport->kategori) == 'Dialog Prestasi Mingguan Daerah' ? 'selected' : '' }}>Dialog Prestasi Mingguan Daerah</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="pengerusi" class="col-md-4 col-form-label text-md-end text-start">Pengerusi</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="pengerusi" name="pengerusi" value="{{ old('pengerusi', $dialogPrestasiReport->pengerusi) }}">
                    </div>
                </div>

                <h6 class="text-primary fw-bold mt-4">KEHADIRAN</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered" id="attendancesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width:5%">Bil</th>
                                <th style="width:45%">Nama</th>
                                <th style="width:40%">Jawatan</th>
                                <th style="width:10%">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="attendancesBody">
                            @forelse ($dialogPrestasiReport->attendances as $index => $attendance)
                                <tr>
                                    <td class="text-center"><span class="att-bil-text fw-bold">{{ $index + 1 }}</span></td>
                                    <td><input type="text" name="attendances[{{ $index }}][nama]" class="form-control autosave-field" value="{{ $attendance->nama }}"></td>
                                    <td><input type="text" name="attendances[{{ $index }}][jawatan]" class="form-control autosave-field" value="{{ $attendance->jawatan }}"></td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center"><span class="att-bil-text fw-bold">1</span></td>
                                    <td><input type="text" name="attendances[0][nama]" class="form-control autosave-field"></td>
                                    <td><input type="text" name="attendances[0][jawatan]" class="form-control autosave-field"></td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="addAttendanceRow"><i class="bi bi-plus-circle"></i> Tambah Kehadiran</button>

                <h6 class="text-primary fw-bold mt-4">ISU DAN TINDAKAN</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered" id="issuesTable">
                        <thead class="table-light">
                             <tr>
                                 <th style="width:5%">Bil</th>
                                 <th style="width:20%">Fokus</th>
                                 <th style="width:20%">Isu</th>
                                 <th style="width:25%">Tindakan</th>
                                 <th style="width:26%">Sektor/Unit</th>
                                 <th style="width:10%">Tindakan</th>
                             </tr>
                        </thead>
                        <tbody id="issuesBody">
                            @forelse ($dialogPrestasiReport->issues as $index => $issue)
                                <tr>
                                    <td class="text-center"><span class="bil-text fw-bold">{{ $index + 1 }}</span></td>
                                    <td><textarea name="issues[{{ $index }}][fokus]" class="form-control autosave-field" rows="2">{{ $issue->fokus }}</textarea></td>
                                    <td><textarea name="issues[{{ $index }}][isu]" class="form-control autosave-field" rows="2">{{ $issue->isu }}</textarea></td>
                                    <td><textarea name="issues[{{ $index }}][tindakan]" class="form-control autosave-field" rows="2">{{ $issue->tindakan }}</textarea></td>
                                    <td>
                                        <select name="issues[{{ $index }}][tagged_sektor_id]" class="form-select autosave-field sektor-select" data-row="{{ $index }}" onchange="loadIssueUnits(this)">
                                            <option value="">-- Pilih Sektor --</option>
                                            @foreach ($sektors->groupBy(fn($s) => $s->pejabatPendidikan?->nama ?? 'Lain-lain') as $officeName => $group)
                                                <optgroup label="{{ $officeName }}">
                                                    @foreach ($group as $s)
                                                        <option value="{{ $s->id }}" {{ $issue->tagged_sektor_id == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <select name="issues[{{ $index }}][tagged_unit_id]" class="form-select autosave-field unit-select mt-1" data-row="{{ $index }}">
                                            <option value="">-- Pilih Unit --</option>
                                            @foreach (($unitsMap[$issue->tagged_sektor_id] ?? collect()) as $u)
                                                <option value="{{ $u->id }}" {{ $issue->tagged_unit_id == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i> Buang</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center"><span class="bil-text fw-bold">1</span></td>
                                    <td><textarea name="issues[0][fokus]" class="form-control autosave-field" rows="2"></textarea></td>
                                    <td><textarea name="issues[0][isu]" class="form-control autosave-field" rows="2"></textarea></td>
                                    <td><textarea name="issues[0][tindakan]" class="form-control autosave-field" rows="2"></textarea></td>
                                    <td>
                                        <select name="issues[0][tagged_sektor_id]" class="form-select autosave-field sektor-select" data-row="0" onchange="loadIssueUnits(this)">
                                            <option value="">-- Pilih Sektor --</option>
                                            @foreach ($sektors->groupBy(fn($s) => $s->pejabatPendidikan?->nama ?? 'Lain-lain') as $officeName => $group)
                                                <optgroup label="{{ $officeName }}">
                                                    @foreach ($group as $s)
                                                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <select name="issues[0][tagged_unit_id]" class="form-select autosave-field unit-select mt-1" data-row="0">
                                            <option value="">-- Pilih Unit --</option>
                                        </select>
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i> Buang</button></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="addRow"><i class="bi bi-plus-circle"></i> Tambah Baris</button>

                <h6 class="text-primary fw-bold mt-4">PENGESAHAN</h6>
                <hr>

                <div class="mb-3 row">
                    <label for="dicatat_oleh" class="col-md-4 col-form-label text-md-end text-start">Dicatat Oleh</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="dicatat_oleh" name="dicatat_oleh" value="{{ old('dicatat_oleh', $dialogPrestasiReport->dicatat_oleh) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="jawatan_pencatat" class="col-md-4 col-form-label text-md-end text-start">Jawatan Pencatat</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="jawatan_pencatat" name="jawatan_pencatat" value="{{ old('jawatan_pencatat', $dialogPrestasiReport->jawatan_pencatat) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="disahkan_oleh" class="col-md-4 col-form-label text-md-end text-start">Disahkan Oleh</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="disahkan_oleh" name="disahkan_oleh" value="{{ old('disahkan_oleh', $dialogPrestasiReport->disahkan_oleh) }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="jawatan_pengesah" class="col-md-4 col-form-label text-md-end text-start">Jawatan Pengesah</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control autosave-field" id="jawatan_pengesah" name="jawatan_pengesah" value="{{ old('jawatan_pengesah', $dialogPrestasiReport->jawatan_pengesah) }}">
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Kemaskini Laporan
                    </button>
                </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let attRowIndex = document.querySelectorAll('#attendancesBody tr').length;
    let rowIndex = document.querySelectorAll('#issuesBody tr').length;
    let autosaveTimer = null;
    let isSaving = false;
    let autosaveQueued = false;

    const allSektors = @json($sektors->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama, 'pejabat' => $s->pejabatPendidikan?->nama ?? 'Lain-lain'])->values());

    function buildSektorOptgroupHtml() {
        let html = '<option value="">-- Pilih Sektor --</option>';
        const grouped = {};
        allSektors.forEach(s => { if (!grouped[s.pejabat]) grouped[s.pejabat] = []; grouped[s.pejabat].push(s); });
        for (const [office, items] of Object.entries(grouped)) {
            html += `<optgroup label="${office}">`;
            items.forEach(s => { html += `<option value="${s.id}">${s.nama}</option>`; });
            html += '</optgroup>';
        }
        return html;
    }

    function loadIssueUnits(selectEl) {
        const sektorId = selectEl.value, row = selectEl.getAttribute('data-row');
        const unitSelect = document.querySelector('.unit-select[data-row="'+row+'"]');
        if(!unitSelect) return;
        unitSelect.innerHTML = '<option value="">-- Memuatkan...</option>';
        if(!sektorId){ unitSelect.innerHTML='<option value="">-- Pilih Unit --</option>'; autosave(); return; }
        fetch('/api/units/'+sektorId).then(r=>r.json()).then(data=>{
            unitSelect.innerHTML='<option value="">-- Pilih Unit --</option>';
            data.forEach(u=>{unitSelect.innerHTML+=`<option value="${u.id}">${u.nama}</option>`;});
            autosave();
        }).catch(()=>{unitSelect.innerHTML='<option value="">-- Ralat --</option>';});
    }

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

        const data = {
            report_id: document.getElementById('report_id').value,
            kategori: document.getElementById('kategori').value,
            pengerusi: document.getElementById('pengerusi').value,
            tarikh: document.getElementById('tarikh').value,
            hari: document.getElementById('hari').value,
            masa: document.getElementById('masa').value,
            tempat: document.getElementById('tempat').value,
            dicatat_oleh: document.getElementById('dicatat_oleh').value,
            jawatan_pencatat: document.getElementById('jawatan_pencatat').value,
            disahkan_oleh: document.getElementById('disahkan_oleh').value,
            jawatan_pengesah: document.getElementById('jawatan_pengesah').value,
            attendances: [],
            issues: []
        };

        document.querySelectorAll('#attendancesBody tr').forEach(function(row) {
            const nama = row.querySelector('input[name*="[nama]"]').value;
            const jawatan = row.querySelector('input[name*="[jawatan]"]').value;
            data.attendances.push({ nama: nama, jawatan: jawatan });
        });

        document.querySelectorAll('#issuesBody tr').forEach(function(row) {
            const isu = row.querySelector('textarea[name*="[isu]"]').value;
            const fokus = row.querySelector('textarea[name*="[fokus]"]').value;
            const tindakan = row.querySelector('textarea[name*="[tindakan]"]').value;
            const tagged_sektor_id = row.querySelector('select[name*="[tagged_sektor_id]"]')?.value || '';
            const tagged_unit_id = row.querySelector('select[name*="[tagged_unit_id]"]')?.value || '';
            data.issues.push({ isu: isu, fokus: fokus, tindakan: tindakan, tagged_sektor_id: tagged_sektor_id, tagged_unit_id: tagged_unit_id });
        });

        isSaving = true;

        fetch('{{ route("dialog-prestasi.autosave") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                document.getElementById('report_id').value = result.report_id;
                statusEl.textContent = 'Disimpan ' + new Date().toLocaleTimeString();
                statusEl.className = 'float-end fw-bold text-success';
            }
        })
        .catch(error => {
            console.error('Autosave error:', error);
            statusEl.textContent = 'Ralat menyimpan';
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

    // Listen for input changes on all autosave fields
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('autosave-field')) {
            autosave();
        }
    });

    // Listen for change events (select, date, time)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('autosave-field')) {
            autosave();
        }
    });

    // Auto-populate "Hari" based on selected "Tarikh"
    document.getElementById('tarikh').addEventListener('change', function() {
        const tarikhValue = this.value;
        if (!tarikhValue) return;

        const date = new Date(tarikhValue + 'T00:00:00');
        const dayIndex = date.getDay();
        const hariMap = ['Ahad', 'Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat', 'Sabtu'];
        document.getElementById('hari').value = hariMap[dayIndex];
        autosave();
    });

    // Add attendance row
    document.getElementById('addAttendanceRow').addEventListener('click', function() {
        const tbody = document.getElementById('attendancesBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center"><span class="att-bil-text fw-bold">${attRowIndex + 1}</span></td>
            <td><input type="text" name="attendances[${attRowIndex}][nama]" class="form-control autosave-field"></td>
            <td><input type="text" name="attendances[${attRowIndex}][jawatan]" class="form-control autosave-field"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>
        `;
        tbody.appendChild(tr);
        attRowIndex++;
        renumberAttendanceRows();
        autosave();
    });

    document.getElementById('attendancesBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-att-row') || e.target.closest('.remove-att-row')) {
            const rows = document.querySelectorAll('#attendancesBody tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                renumberAttendanceRows();
                autosave();
            } else {
                alert('Sekurang-kurangnya satu baris diperlukan.');
            }
        }
    });

    // Paste handler
    document.getElementById('attendancesBody').addEventListener('paste', function(e) {
        const target = e.target.closest('input[name*="[nama]"]');
        if (!target) return;

        e.preventDefault();
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        if (!pastedText) return;

        const names = pastedText.split(/[,\n\r]+/).map(function(s) { return s.trim(); }).filter(function(s) { return s !== ''; });
        if (names.length === 0) return;

        target.value = names[0];

        for (let i = 1; i < names.length; i++) {
            const tbody = document.getElementById('attendancesBody');
            const tr = document.createElement('tr');
            tr.innerHTML =
                '<td class="text-center"><span class="att-bil-text fw-bold">' + (attRowIndex + 1) + '</span></td>' +
                '<td><input type="text" name="attendances[' + attRowIndex + '][nama]" class="form-control autosave-field" value="' + names[i] + '"></td>' +
                '<td><input type="text" name="attendances[' + attRowIndex + '][jawatan]" class="form-control autosave-field"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>';
            tbody.appendChild(tr);
            attRowIndex++;
        }

        renumberAttendanceRows();
        autosave();
    });

    function renumberAttendanceRows() {
        const rows = document.querySelectorAll('#attendancesBody tr');
        rows.forEach((row, index) => {
            const bilText = row.querySelector('.att-bil-text');
            if (bilText) bilText.textContent = index + 1;
            row.querySelector('input[name*="[nama]"]').name = `attendances[${index}][nama]`;
            row.querySelector('input[name*="[jawatan]"]').name = `attendances[${index}][jawatan]`;
        });
        attRowIndex = rows.length;
    }

    // Add issue row
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.getElementById('issuesBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center"><span class="bil-text fw-bold">${rowIndex + 1}</span></td>
            <td><textarea name="issues[${rowIndex}][fokus]" class="form-control autosave-field" rows="2"></textarea></td>
            <td><textarea name="issues[${rowIndex}][isu]" class="form-control autosave-field" rows="2"></textarea></td>
            <td><textarea name="issues[${rowIndex}][tindakan]" class="form-control autosave-field" rows="2"></textarea></td>
            <td><select name="issues[${rowIndex}][tagged_sektor_id]" class="form-select autosave-field sektor-select" data-row="${rowIndex}" onchange="loadIssueUnits(this)">${buildSektorOptgroupHtml()}</select><select name="issues[${rowIndex}][tagged_unit_id]" class="form-select autosave-field unit-select mt-1" data-row="${rowIndex}"><option value="">-- Pilih Unit --</option></select></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i> Buang</button></td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        renumberRows();
        autosave();
    });

    document.getElementById('issuesBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('#issuesBody tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                renumberRows();
                autosave();
            } else {
                alert('Sekurang-kurangnya satu baris diperlukan.');
            }
        }
    });

    function renumberRows() {
        const rows = document.querySelectorAll('#issuesBody tr');
        rows.forEach((row, index) => {
            const bilText = row.querySelector('.bil-text');
            if (bilText) { bilText.textContent = index + 1; }
            row.querySelector('textarea[name*="[fokus]"]').name = `issues[${index}][fokus]`;
            row.querySelector('textarea[name*="[isu]"]').name = `issues[${index}][isu]`;
            row.querySelector('textarea[name*="[tindakan]"]').name = `issues[${index}][tindakan]`;
            const sektorSelect = row.querySelector('select[name*="[tagged_sektor_id]"]');
            if (sektorSelect) { sektorSelect.name = `issues[${index}][tagged_sektor_id]`; sektorSelect.setAttribute('data-row', index); }
            const unitSelect = row.querySelector('select[name*="[tagged_unit_id]"]');
            if (unitSelect) { unitSelect.name = `issues[${index}][tagged_unit_id]`; unitSelect.setAttribute('data-row', index); }
        });
        rowIndex = rows.length;
    }

    // Auto-populate "Hari" on page load if tarikh already has a value
    (function() {
        const tarikhInput = document.getElementById('tarikh');
        if (tarikhInput.value) {
            tarikhInput.dispatchEvent(new Event('change'));
        }
    })();

    // Autosave on page unload
    window.addEventListener('beforeunload', function() {
        if (autosaveTimer) {
            clearTimeout(autosaveTimer);
        }
    });
</script>
@endpush