@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>

    @if (!empty($dashboardData) && count($dashboardData) > 0)
        <div class="row">
            @foreach ($dashboardData as $item)
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-2 me-2 text-primary"><i class="bi {{ $item['icon'] }}"></i></span>
                                <h6 class="card-title mb-0 fw-bold text-dark">{{ $item['kategori'] }}</h6>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="bi bi-check-circle-fill fs-4"></i>
                                        <h3 class="mb-0 fw-bold">{{ $item['selesai'] }}</h3>
                                        <small class="text-muted">Selesai</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-warning">
                                        <i class="bi bi-hourglass-split fs-4"></i>
                                        <h3 class="mb-0 fw-bold">{{ $item['dalam_progress'] }}</h3>
                                        <small class="text-muted">Dalam Progress</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-danger">
                                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                        <h3 class="mb-0 fw-bold">{{ $item['belum_selesai'] }}</h3>
                                        <small class="text-muted">Belum Selesai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="badge bg-secondary">Jumlah: {{ $item['jumlah'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-bar-chart fs-1"></i>
                <p class="mt-2">Tiada data laporan untuk dipaparkan.</p>
            </div>
        </div>
    @endif

    @canany(['view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi'])
        <div class="mt-3">
            <a class="btn btn-info" href="{{ route('dialog-prestasi.index') }}">
                <i class="bi bi-file-earmark-text"></i> Dialog Prestasi
            </a>
        </div>
    @endcanany
</div>
@endsection