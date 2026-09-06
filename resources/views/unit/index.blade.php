@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <i class="bi bi-stack me-2"></i>Pengurusan Unit
                </div>
                <div class="float-end">
                    <a href="{{ route('sektor.index') }}" class="btn btn-outline-secondary btn-sm me-1">
                        <i class="bi bi-diagram-3"></i> Sektor
                    </a>
                    <a href="{{ route('unit.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Unit
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                @endif

                @php $grouped = $units->groupBy(fn($u) => ($u->sektor->pejabatPendidikan?->nama ?? 'Lain-lain') . ' › ' . $u->sektor->nama); @endphp

                @forelse ($grouped as $sektorLabel => $items)
                    <h6 class="mt-3 mb-2 fw-bold text-primary">
                        <i class="bi bi-folder2-open"></i> {{ $sektorLabel }}
                        <small class="text-muted fw-normal">(seret untuk susun semula)</small>
                    </h6>
                    <ul class="list-group sortable-unit mb-3" data-sektor-id="{{ $items->first()->sektor_id }}">
                        @foreach ($items as $unit)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $unit->id }}">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-grip-vertical text-muted me-2 handle" style="cursor:grab"></i>
                                <span>
                                    <strong>{{ $unit->nama }}</strong>
                                    <small class="text-muted ms-2">{{ $unit->kod }}</small>
                                </span>
                            </span>
                            <span class="text-nowrap">
                                <a href="{{ route('unit.edit', $unit->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Padam unit ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Padam"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @empty
                    <p class="text-muted text-center py-4">Tiada unit. Klik <strong>Tambah Unit</strong> untuk mula.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.querySelectorAll('.sortable-unit').forEach(el => {
    new Sortable(el, {
        group: 'units',
        handle: '.handle',
        animation: 150,
        onEnd: function (evt) {
            const targetList = evt.to;
            const sektorId = targetList.getAttribute('data-sektor-id');
            const order = Array.from(targetList.children).map(li => li.getAttribute('data-id'));
            fetch('{{ route('unit.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ sektor_id: sektorId, order: order })
            });
        }
    });
});
</script>
@endpush
