@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <span><i class="bi bi-file-earmark-text me-2"></i>Senarai Laporan Dialog Prestasi</span>
        @can('create-dialog-prestasi')
            <a href="{{ route('dialog-prestasi.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Laporan</a>
        @endcan
    </div>
    <div class="card-body">

        <form method="GET" action="{{ route('dialog-prestasi.index') }}" class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="tarikh" id="filterTarikh" class="form-control" value="{{ request('tarikh') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tapis</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dialog-prestasi.index') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>

        <form id="bulkDeleteForm" action="{{ route('dialog-prestasi.bulk-destroy') }}" method="POST" onsubmit="return confirm('Anda pasti mahu memadam SEMUA laporan yang dipilih? Tindakan ini TIDAK boleh dibatalkan.');">
            @csrf
            @method('DELETE')

            @can('delete-dialog-prestasi')
            <div class="mb-2" id="bulkDeleteBar" style="display:none;">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Padam Laporan Dipilih (<span id="selectedCount">0</span>)
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAllBtn">
                    <i class="bi bi-x-circle"></i> Nyahpilih Semua
                </button>
            </div>
            @endcan

            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            @can('delete-dialog-prestasi')
                            <th style="width:3%">
                                <input type="checkbox" id="selectAll" title="Pilih Semua">
                            </th>
                            @endcan
                            <th style="width:5%">#</th>
                            <th style="width:18%">Pengerusi</th>
                            <th style="width:12%">Tarikh</th>
                            <th style="width:12%">Hari</th>
                            <th style="width:10%">Masa</th>
                            <th style="width:18%">Tempat</th>
                            <th style="width:22%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $index => $report)
                            @php $colspan = auth()->user()?->can('delete-dialog-prestasi') ? 8 : 7; @endphp
                            <tr>
                                @can('delete-dialog-prestasi')
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $report->id }}" class="report-checkbox">
                                </td>
                                @endcan
                                <td>{{ $reports->firstItem() + $index }}</td>
                                <td><span class="fw-medium">{{ $report->pengerusi ?: '-' }}</span></td>
                                <td>
                                    @if ($report->tarikh)
                                        <span class="badge bg-light text-dark">
                                            {{ $report->tarikh instanceof \Carbon\Carbon ? $report->tarikh->format('d/m/Y') : date('d/m/Y', strtotime($report->tarikh)) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $report->hari ?: '-' }}</td>
                                <td>
                                    <i class="bi bi-clock text-muted me-1"></i>
                                    @if ($report->masa)
                                        {{ $report->masa instanceof \Carbon\Carbon ? $report->masa->format('H:i') : substr($report->masa, 0, 5) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <i class="bi bi-geo-alt text-muted me-1"></i>
                                    {{ $report->tempat ?: '-' }}
                                </td>
                                <td>
                                     <div class="d-flex gap-1 flex-wrap">
                                         <a href="{{ route('dialog-prestasi.show', $report->id) }}" class="btn btn-warning btn-sm" title="Lihat"><i class="bi bi-eye"></i></a>
                                         @can('edit-dialog-prestasi')
                                             <a href="{{ route('dialog-prestasi.edit', ['dialogPrestasiReport' => $report->id]) }}" class="btn btn-primary btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                         @endcan
                                         @can('feedback-dialog-prestasi')
                                             <a href="{{ route('dialog-prestasi.feedback', $report->id) }}" class="btn btn-info btn-sm" title="Maklum Balas"><i class="bi bi-chat-dots"></i></a>
                                         @endcan
                                         <a href="{{ route('dialog-prestasi.print', $report->id) }}" class="btn btn-danger btn-sm" target="_blank" title="PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                                        @can('delete-dialog-prestasi')
                                            <button type="button" class="btn btn-danger btn-sm delete-single-btn" data-id="{{ $report->id }}" data-url="{{ route('dialog-prestasi.destroy', $report->id) }}" title="Padam">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $colspan ?? 7 }}" class="text-center text-muted">Tiada laporan Dialog Prestasi ditemui.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

<div class="d-flex justify-content-center mt-3">
            {{ $reports->links() }}
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.report-checkbox');
        const bulkDeleteBar = document.getElementById('bulkDeleteBar');
        const selectedCount = document.getElementById('selectedCount');
        const deselectAllBtn = document.getElementById('deselectAllBtn');

        function updateBulkBar() {
            const checked = document.querySelectorAll('.report-checkbox:checked');
            const count = checked.length;
            selectedCount.textContent = count;
            bulkDeleteBar.style.display = count > 0 ? '' : 'none';
        }

        // Select / Deselect All
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkBar();
            });
        }

        // Individual checkbox change
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkBar);
        });

        // Deselect all button
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function () {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkBar();
            });
        }

        // Single delete via button (not form submit)
        document.querySelectorAll('.delete-single-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const url = this.getAttribute('data-url');
                if (confirm('Anda pasti mahu memadam laporan #' + id + '?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
