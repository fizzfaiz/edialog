@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start fw-bold">Nama</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama penuh">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start fw-bold">Email</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="nama@example.com">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start fw-bold">Kata Laluan</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimum 8 aksara" autocomplete="new-password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start fw-bold">Sahkan Kata Laluan</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulang kata laluan" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start fw-bold">Peranan</label>
                        <div class="col-md-6">
                            <select class="form-select @error('roles') is-invalid @enderror" multiple id="roles" name="roles[]" style="height: 150px;">
                                @forelse ($roles as $role)
                                    @if ($role !== 'Super Admin' || Auth::user()->hasRole('Super Admin'))
                                        <option value="{{ $role }}" {{ in_array($role, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                    @endif
                                @empty
                                @endforelse
                            </select>
                            <small class="text-muted">Gunakan <kbd>Ctrl</kbd> + klik untuk pilih lebih daripada satu peranan.</small>
                            @error('roles')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="pejabat_pendidikan_id" class="col-md-4 col-form-label text-md-end text-start fw-bold">Pejabat Pendidikan</label>
                        <div class="col-md-6">
                            <select class="form-select @error('pejabat_pendidikan_id') is-invalid @enderror" id="pejabat_pendidikan_id" name="pejabat_pendidikan_id" onchange="loadSektors(this.value)">
                                <option value="">-- Pilih Pejabat --</option>
                                @foreach ($pejabats as $pejabat)
                                    <option value="{{ $pejabat->id }}" {{ old('pejabat_pendidikan_id') == $pejabat->id ? 'selected' : '' }}>
                                        [{{ strtoupper($pejabat->jenis) }}] {{ $pejabat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pejabat_pendidikan_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Pejabat yang dikaitkan dengan pengguna ini untuk kawalan data hierarki.</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sektor_id" class="col-md-4 col-form-label text-md-end text-start fw-bold">Sektor</label>
                        <div class="col-md-6">
                            <select class="form-select @error('sektor_id') is-invalid @enderror" id="sektor_id" name="sektor_id" onchange="loadUnits(this.value)">
                                <option value="">-- Pilih Sektor --</option>
                            </select>
                            @error('sektor_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Sektor di dalam pejabat yang dipilih.</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="unit_id" class="col-md-4 col-form-label text-md-end text-start fw-bold">Unit</label>
                        <div class="col-md-6">
                            <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id">
                                <option value="">-- Pilih Unit --</option>
                            </select>
                            @error('unit_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Unit di dalam sektor yang dipilih.</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Simpan Pengguna
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load sektors based on selected pejabat
    function loadSektors(pejabatId) {
        const sektorSelect = document.getElementById('sektor_id');
        const unitSelect = document.getElementById('unit_id');

        sektorSelect.innerHTML = '<option value="">-- Memuatkan...</option>';
        unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';

        if (!pejabatId) {
            sektorSelect.innerHTML = '<option value="">-- Pilih Sektor --</option>';
            return;
        }

        fetch(`/api/sektors/${pejabatId}`)
            .then(res => res.json())
            .then(data => {
                sektorSelect.innerHTML = '<option value="">-- Pilih Sektor --</option>';
                data.forEach(sektor => {
                    sektorSelect.innerHTML += `<option value="${sektor.id}">${sektor.nama}</option>`;
                });

                // If old value exists, select it
                const oldSektor = '{{ old('sektor_id') }}';
                if (oldSektor) {
                    sektorSelect.value = oldSektor;
                    if (oldSektor) loadUnits(oldSektor);
                }
            })
            .catch(err => {
                sektorSelect.innerHTML = '<option value="">-- Ralat memuat --</option>';
                console.error(err);
            });
    }

    // Load units based on selected sektor
    function loadUnits(sektorId) {
        const unitSelect = document.getElementById('unit_id');

        if (!sektorId) {
            unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
            return;
        }

        unitSelect.innerHTML = '<option value="">-- Memuatkan...</option>';

        fetch(`/api/units/${sektorId}`)
            .then(res => res.json())
            .then(data => {
                unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
                data.forEach(unit => {
                    unitSelect.innerHTML += `<option value="${unit.id}">${unit.nama}</option>`;
                });

                // If old value exists, select it
                const oldUnit = '{{ old('unit_id') }}';
                if (oldUnit) {
                    unitSelect.value = oldUnit;
                }
            })
            .catch(err => {
                unitSelect.innerHTML = '<option value="">-- Ralat memuat --</option>';
                console.error(err);
            });
    }

    // On page load, if pejabat already selected, load its sektors
    document.addEventListener('DOMContentLoaded', function() {
        const pejabatSelect = document.getElementById('pejabat_pendidikan_id');
        if (pejabatSelect.value) {
            loadSektors(pejabatSelect.value);
        }
    });
</script>
@endpush