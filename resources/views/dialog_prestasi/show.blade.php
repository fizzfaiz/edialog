@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
Butiran Laporan Dialog Prestasi
                </div>
                <div class="float-end">
                    <a href="{{ route('dialog-prestasi.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                     @can('edit-dialog-prestasi')
                        <a href="{{ route('dialog-prestasi.edit', ['dialogPrestasiReport' => $dialogPrestasiReport->id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                    @endcan
                    <a href="{{ route('dialog-prestasi.print', $dialogPrestasiReport->id) }}" class="btn btn-danger btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
                </div>
            </div>
            <div class="card-body">

                <h6 class="text-primary fw-bold">MAKLUMAT MESYUARAT</h6>
                <hr>

                <div class="row">
                    <label for="pengerusi" class="col-md-4 col-form-label text-md-end text-start"><strong>Pengerusi:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->pengerusi }}
                    </div>
                </div>

                <div class="row">
                    <label for="tarikh" class="col-md-4 col-form-label text-md-end text-start"><strong>Tarikh:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->tarikh ? $dialogPrestasiReport->tarikh->format('d/m/Y') : '-' }}
                    </div>
                </div>

                <div class="row">
                    <label for="hari" class="col-md-4 col-form-label text-md-end text-start"><strong>Hari:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->hari }}
                    </div>
                </div>

                <div class="row">
                    <label for="masa" class="col-md-4 col-form-label text-md-end text-start"><strong>Masa:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->masa ? $dialogPrestasiReport->masa->format('H:i') : '-' }}
                    </div>
                </div>

                <div class="row">
                    <label for="tempat" class="col-md-4 col-form-label text-md-end text-start"><strong>Tempat:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->tempat }}
                    </div>
                </div>

                <div class="row">
                    <label for="kategori" class="col-md-4 col-form-label text-md-end text-start"><strong>Kategori:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->kategori ?: '-' }}
                    </div>
                </div>

                    <h6 class="text-primary fw-bold mt-4">KEHADIRAN</h6>
                    <hr>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:5%">Bil</th>
                                    <th style="width:45%">Nama</th>
                                    <th style="width:50%">Jawatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dialogPrestasiReport->attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->nama }}</td>
                                        <td>{{ $attendance->jawatan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tiada rekod kehadiran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                <h6 class="text-primary fw-bold mt-4">ISU DAN TINDAKAN</h6>
                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                 <th style="width:5%">Bil</th>
                                 <th style="width:20%">Fokus</th>
                                 <th style="width:20%">Isu</th>
                                 <th style="width:25%">Tindakan</th>
                                 <th style="width:20%">Sektor/Pegawai</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse ($dialogPrestasiReport->issues as $index => $issue)
                                 <tr>
                                     <td class="text-center">{{ $index + 1 }}</td>
                                     <td>{{ $issue->fokus }}</td>
                                     <td>{{ $issue->isu }}</td>
                                     <td>{{ $issue->tindakan }}</td>
                                     <td>{{ $issue->sektor_pegawai }}</td>
                                 </tr>
                             @empty
                                 <tr>
                                     <td colspan="5" class="text-center text-muted">Tiada isu dan tindakan.</td>
                                 </tr>
                             @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="text-primary fw-bold mt-4">PENGESAHAN</h6>
                <hr>

                <div class="row">
                    <label for="dicatat_oleh" class="col-md-4 col-form-label text-md-end text-start"><strong>Dicatat Oleh:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->dicatat_oleh }}
                    </div>
                </div>

                <div class="row">
                    <label for="jawatan_pencatat" class="col-md-4 col-form-label text-md-end text-start"><strong>Jawatan Pencatat:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->jawatan_pencatat }}
                    </div>
                </div>

                <div class="row">
                    <label for="disahkan_oleh" class="col-md-4 col-form-label text-md-end text-start"><strong>Disahkan Oleh:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->disahkan_oleh }}
                    </div>
                </div>

                <div class="row">
                    <label for="jawatan_pengesah" class="col-md-4 col-form-label text-md-end text-start"><strong>Jawatan Pengesah:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $dialogPrestasiReport->jawatan_pengesah }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
