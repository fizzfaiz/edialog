@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <i class="bi bi-diagram-3 me-2"></i>Pengurusan Sektor
                </div>
                <div class="float-end">
                    <a href="{{ route('unit.index') }}" class="btn btn-outline-secondary btn-sm me-1">
                        <i class="bi bi-stack"></i> Unit
                    </a>
                    <a href="{{ route('sektor.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Sektor
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @endif

                @php $grouped = $sektors->groupBy(fn($s) => $s->pejabatPendidikan?->nama ?? 'Lain-lain'); @endphp

                @forelse ($grouped as $pejabat => $items)
                    <h6 class="mt-3 mb-2 fw-bold text-primary">
                        <i class="bi bi-building"></i> {{ $pejabat }}
                        <small class="text-muted fw-normal">(seret untuk susun semula)</small>
                    </h6>
                    <ul class="list-group sortable-sektor mb-3">
                        @foreach ($items as $sektor)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $sektor->id }}">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-grip-vertical text-muted me-2 handle" style="cursor:grab"></i>
                                <span>
                                    <strong>{{ $sektor->nama }}</strong>
                                    <small class="text-muted ms-2">{{ $sektor->kod }} &middot; {{ $sektor->units_count }} unit</small>
                                </span>
                            </span>
                            <span class="text-nowrap">
                                <a href="{{ route('sektor.edit', $sektor->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('sektor.destroy', $sektor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Padam sektor ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Padam"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @empty
                    <p class="text-muted text-center py-4">Tiada sektor. Klik <strong>Tambah Sektor</strong> untuk mula.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.querySelectorAll('.sortable-sektor').forEach(el => {
    new Sortable(el, {
        handle: '.handle',
        animation: 150,
        onEnd: function () {
            const order = Array.from(el.children).map(li => li.getAttribute('data-id'));
            fetch('{{ route('sektor.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order: order })
            });
        }
    });
});
</script>
@endpush
