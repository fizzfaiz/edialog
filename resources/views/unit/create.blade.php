@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start"><i class="bi bi-plus-circle me-2"></i>Tambah Unit</div>
                <div class="float-end"><a href="{{ route('unit.index') }}" class="btn btn-primary btn-sm">&larr; Kembali</a></div>
            </div>
            <div class="card-body">
                <form action="{{ route('unit.store') }}" method="POST">
                    @csrf

                    <div class="mb-3 row">
                        <label for="nama" class="col-md-4 col-form-label text-md-end fw-bold">Nama Unit</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="cth: Unit Rendah">
                            @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="kod" class="col-md-4 col-form-label text-md-end fw-bold">Kod</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('kod') is-invalid @enderror" id="kod" name="kod" value="{{ old('kod') }}" placeholder="cth: PPDMT01-01">
                            @error('kod') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sektor_id" class="col-md-4 col-form-label text-md-end fw-bold">Sektor</label>
                        <div class="col-md-6">
                            <select class="form-select @error('sektor_id') is-invalid @enderror" id="sektor_id" name="sektor_id">
                                <option value="">-- Pilih Sektor --</option>
                                @foreach ($sektors as $sektor)
                                    <option value="{{ $sektor->id }}" {{ old('sektor_id') == $sektor->id ? 'selected' : '' }}>{{ $sektor->pejabatPendidikan?->nama ?? '' }} › {{ $sektor->nama }}</option>
                                @endforeach
                            </select>
                            @error('sektor_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
