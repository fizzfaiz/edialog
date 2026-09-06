@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">Tambah Laporan Dialog Prestasi</div>
                <div class="float-end">
                    <a href="{{ route('dialog-prestasi.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle"></i> Borang ini disimpan secara automatik setiap kali data ditaip.
                    <span id="autosaveStatus" class="float-end fw-bold text-success">Sedia</span>
                </div>

                <form id="dialogPrestasiForm" action="{{ route('dialog-prestasi.store') }}" method="POST">
                    @csrf
                <input type="hidden" id="report_id" name="report_id" value="">

                {{-- Level Info -- read-only display + hidden inputs --}}
                <div class="card bg-light mb-3">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>🏢 Pejabat:</strong>
                                <span>{{ $office?->nama ?? 'Tiada' }}</span>
                                <input type="hidden" name="pejabat_pendidikan_id" value="{{ $office?->id }}">
                            </div>
                            <div class="col-md-4">
                                <strong>📂 Sektor:</strong>
                                <span>{{ $user->sektor?->nama ?? '-' }}</span>
                                <input type="hidden" name="sektor_id" value="{{ $user->sektor_id }}">
                            </div>
                            <div class="col-md-4">
                                <strong>📋 Unit:</strong>
                                <span>{{ $user->unit?->nama ?? '-' }}</span>
                                <input type="hidden" name="unit_id" value="{{ $user->unit_id }}">
                            </div>
                        </div>
                        <small class="text-muted">Laporan direkod di bawah pejabat, sektor, dan unit anda secara automatik.</small>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="tarikh" class="col-md-4 col-form-label text-md-end text-start">Tarikh</label>
                    <div class="col-md-6"><input type="date" class="form-control autosave-field" id="tarikh" name="tarikh" value="{{ old('tarikh') }}"></div>
                </div>
                <div class="mb-3 row">
                    <label for="hari" class="col-md-4 col-form-label text-md-end text-start">Hari</label>
                    <div class="col-md-6"><input type="text" class="form-control" id="hari" name="hari" value="{{ old('hari') }}" readonly></div>
                </div>
                <div class="mb-3 row">
                    <label for="masa" class="col-md-4 col-form-label text-md-end text-start">Masa</label>
                    <div class="col-md-6"><input type="time" class="form-control autosave-field" id="masa" name="masa" value="{{ old('masa') }}"></div>
                </div>
                <div class="mb-3 row">
                    <label for="tempat" class="col-md-4 col-form-label text-md-end text-start">Tempat</label>
                    <div class="col-md-6"><input type="text" class="form-control autosave-field" id="tempat" name="tempat" value="{{ old('tempat') }}"></div>
                </div>
                <div class="mb-3 row">
                    <label for="kategori" class="col-md-4 col-form-label text-md-end text-start">Kategori</label>
                    <div class="col-md-6">
                        <select class="form-select autosave-field" id="kategori" name="kategori">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Dialog Prestasi Negeri">Dialog Prestasi Negeri</option>
                            <option value="Dialog Prestasi Berfokus Negeri">Dialog Prestasi Berfokus Negeri</option>
                            <option value="Dialog Prestasi Berfokus Daerah">Dialog Prestasi Berfokus Daerah</option>
                            <option value="Dialog Prestasi PPD">Dialog Prestasi PPD</option>
                            <option value="Dialog Prestasi Daerah">Dialog Prestasi Daerah</option>
                            <option value="Dialog Prestasi Mingguan Daerah">Dialog Prestasi Mingguan Daerah</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="pengerusi" class="col-md-4 col-form-label text-md-end text-start">Pengerusi</label>
                    <div class="col-md-6"><input type="text" class="form-control autosave-field" id="pengerusi" name="pengerusi" value="{{ old('pengerusi') }}"></div>
                </div>

                <h6 class="text-primary fw-bold mt-4">KEHADIRAN</h6><hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="attendancesTable">
                        <thead class="table-light"><tr><th style="width:5%">Bil</th><th style="width:45%">Nama</th><th style="width:40%">Jawatan</th><th style="width:10%">Tindakan</th></tr></thead>
                        <tbody id="attendancesBody">
                            <tr>
                                <td class="text-center"><span class="att-bil-text fw-bold">1</span></td>
                                <td><input type="text" name="attendances[0][nama]" class="form-control autosave-field"></td>
                                <td><input type="text" name="attendances[0][jawatan]" class="form-control autosave-field"></td>
                                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="addAttendanceRow"><i class="bi bi-plus-circle"></i> Tambah Kehadiran</button>

                <h6 class="text-primary fw-bold mt-4">ISU DAN TINDAKAN</h6><hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="issuesTable">
                        <thead class="table-light"><tr>
                            <th style="width:4%">Bil</th>
                            <th style="width:14%">Fokus</th>
                            <th style="width:14%">Isu</th>
                            <th style="width:18%">Tindakan</th>
                            <th style="width:26%">Sektor/Unit</th>
                            <th style="width:10%">Tindakan</th>
                        </tr></thead>
                        <tbody id="issuesBody">
                            <tr data-row="0">
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
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success btn-sm mb-3" id="addRow"><i class="bi bi-plus-circle"></i> Tambah Baris</button>

                <h6 class="text-primary fw-bold mt-4">PENGESAHAN</h6><hr>
                <div class="mb-3 row"><label for="dicatat_oleh" class="col-md-4 col-form-label text-md-end text-start">Dicatat Oleh</label><div class="col-md-6"><input type="text" class="form-control autosave-field" id="dicatat_oleh" name="dicatat_oleh" value="{{ old('dicatat_oleh') }}"></div></div>
                <div class="mb-3 row"><label for="jawatan_pencatat" class="col-md-4 col-form-label text-md-end text-start">Jawatan Pencatat</label><div class="col-md-6"><input type="text" class="form-control autosave-field" id="jawatan_pencatat" name="jawatan_pencatat" value="{{ old('jawatan_pencatat') }}"></div></div>
                <div class="mb-3 row"><label for="disahkan_oleh" class="col-md-4 col-form-label text-md-end text-start">Disahkan Oleh</label><div class="col-md-6"><input type="text" class="form-control autosave-field" id="disahkan_oleh" name="disahkan_oleh" value="{{ old('disahkan_oleh') }}"></div></div>
                <div class="mb-3 row"><label for="jawatan_pengesah" class="col-md-4 col-form-label text-md-end text-start">Jawatan Pengesah</label><div class="col-md-6"><input type="text" class="form-control autosave-field" id="jawatan_pengesah" name="jawatan_pengesah" value="{{ old('jawatan_pengesah') }}"></div></div>

                <div class="text-center mt-4"><button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Simpan Laporan</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let attRowIndex = 1, rowIndex = 1, autosaveTimer = null, isSaving = false, autosaveQueued = false;
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

    function autosave(d=1000){clearTimeout(autosaveTimer);autosaveTimer=setTimeout(performAutosave,d);}
    function performAutosave(){
        if(isSaving){autosaveQueued=true;return;}
        const st=document.getElementById('autosaveStatus');st.textContent='Menyimpan...';st.className='float-end fw-bold text-warning';
        const d={report_id:document.getElementById('report_id').value,kategori:document.getElementById('kategori').value,pengerusi:document.getElementById('pengerusi').value,tarikh:document.getElementById('tarikh').value,hari:document.getElementById('hari').value,masa:document.getElementById('masa').value,tempat:document.getElementById('tempat').value,dicatat_oleh:document.getElementById('dicatat_oleh').value,jawatan_pencatat:document.getElementById('jawatan_pencatat').value,disahkan_oleh:document.getElementById('disahkan_oleh').value,jawatan_pengesah:document.getElementById('jawatan_pengesah').value,attendances:[],issues:[]};
        document.querySelectorAll('#attendancesBody tr').forEach(r=>{d.attendances.push({nama:r.querySelector('input[name*="[nama]"]').value,jawatan:r.querySelector('input[name*="[jawatan]"]').value})});
        document.querySelectorAll('#issuesBody tr').forEach(r=>{d.issues.push({isu:r.querySelector('textarea[name*="[isu]"]').value,fokus:r.querySelector('textarea[name*="[fokus]"]').value,tindakan:r.querySelector('textarea[name*="[tindakan]"]').value,tagged_sektor_id:r.querySelector('select[name*="[tagged_sektor_id]"]')?.value||'',tagged_unit_id:r.querySelector('select[name*="[tagged_unit_id]"]')?.value||''})});
        isSaving=true;
        fetch('{{ route("dialog-prestasi.autosave") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.ok?r.json():r.json().then(e=>{throw new Error(e.message||'HTTP Error')})).then(r=>{if(r.success){document.getElementById('report_id').value=r.report_id;st.textContent='Disimpan '+new Date().toLocaleTimeString();st.className='float-end fw-bold text-success'}}).catch(e=>{st.textContent='Ralat: '+(e.message||'Ralat');st.className='float-end fw-bold text-danger'}).finally(()=>{isSaving=false;if(autosaveQueued){autosaveQueued=false;autosave(0)}});
    }
    document.addEventListener('input',e=>{if(e.target.classList.contains('autosave-field'))autosave()});
    document.addEventListener('change',e=>{if(e.target.classList.contains('autosave-field'))autosave()});
    document.getElementById('tarikh').addEventListener('change',function(){if(!this.value)return;document.getElementById('hari').value=['Ahad','Isnin','Selasa','Rabu','Khamis','Jumaat','Sabtu'][new Date(this.value+'T00:00:00').getDay()];autosave()});
    document.getElementById('addAttendanceRow').addEventListener('click',()=>{const tr=document.createElement('tr');tr.innerHTML=`<td class="text-center"><span class="att-bil-text fw-bold">${attRowIndex+1}</span></td><td><input type="text" name="attendances[${attRowIndex}][nama]" class="form-control autosave-field"></td><td><input type="text" name="attendances[${attRowIndex}][jawatan]" class="form-control autosave-field"></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-att-row"><i class="bi bi-trash"></i> Buang</button></td>`;document.getElementById('attendancesBody').appendChild(tr);attRowIndex++;renumberAttendanceRows();autosave()});
    document.getElementById('attendancesBody').addEventListener('click',e=>{if(e.target.closest('.remove-att-row')){if(document.querySelectorAll('#attendancesBody tr').length<=1)return alert('Sekurang-kurangnya satu baris diperlukan.');e.target.closest('tr').remove();renumberAttendanceRows();autosave()}});
    function renumberAttendanceRows(){document.querySelectorAll('#attendancesBody tr').forEach((r,i)=>{r.querySelector('.att-bil-text').textContent=i+1;r.querySelector('input[name*="[nama]"]').name=`attendances[${i}][nama]`;r.querySelector('input[name*="[jawatan]"]').name=`attendances[${i}][jawatan]`});attRowIndex=document.querySelectorAll('#attendancesBody tr').length}
    document.getElementById('addRow').addEventListener('click',()=>{const tr=document.createElement('tr');tr.setAttribute('data-row',rowIndex);tr.innerHTML=`<td class="text-center"><span class="bil-text fw-bold">${rowIndex+1}</span></td><td><textarea name="issues[${rowIndex}][fokus]" class="form-control autosave-field" rows="2"></textarea></td><td><textarea name="issues[${rowIndex}][isu]" class="form-control autosave-field" rows="2"></textarea></td><td><textarea name="issues[${rowIndex}][tindakan]" class="form-control autosave-field" rows="2"></textarea></td><td><select name="issues[${rowIndex}][tagged_sektor_id]" class="form-select autosave-field sektor-select" data-row="${rowIndex}" onchange="loadIssueUnits(this)">${buildSektorOptgroupHtml()}</select><select name="issues[${rowIndex}][tagged_unit_id]" class="form-select autosave-field unit-select mt-1" data-row="${rowIndex}"><option value="">-- Pilih Unit --</option></select></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i> Buang</button></td>`;document.getElementById('issuesBody').appendChild(tr);rowIndex++;renumberRows();autosave()});
    document.getElementById('issuesBody').addEventListener('click',e=>{if(e.target.closest('.remove-row')){if(document.querySelectorAll('#issuesBody tr').length<=1)return alert('Sekurang-kurangnya satu baris diperlukan.');e.target.closest('tr').remove();renumberRows();autosave()}});
    function renumberRows(){document.querySelectorAll('#issuesBody tr').forEach((r,i)=>{r.setAttribute('data-row',i);const bt=r.querySelector('.bil-text');if(bt)bt.textContent=i+1;r.querySelector('textarea[name*="[fokus]"]').name=`issues[${i}][fokus]`;r.querySelector('textarea[name*="[isu]"]').name=`issues[${i}][isu]`;r.querySelector('textarea[name*="[tindakan]"]').name=`issues[${i}][tindakan]`;const ss=r.querySelector('select[name*="[tagged_sektor_id]"]');if(ss){ss.name=`issues[${i}][tagged_sektor_id]`;ss.setAttribute('data-row',i)}const us=r.querySelector('select[name*="[tagged_unit_id]"]');if(us){us.name=`issues[${i}][tagged_unit_id]`;us.setAttribute('data-row',i)}});rowIndex=document.querySelectorAll('#issuesBody tr').length}
    (function(){const ti=document.getElementById('tarikh');if(ti.value)ti.dispatchEvent(new Event('change'))})();
</script>
@endpush