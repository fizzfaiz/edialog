@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <span><i class="bi bi-people-fill me-2"></i>Pengurusan Pengguna</span>
        @can('create-user')
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Pengguna</a>
        @endcan
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:5%">#</th>
                        <th style="width:20%">Nama</th>
                        <th style="width:25%">Email</th>
                        <th style="width:25%">Peranan</th>
                        <th style="width:20%">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 0; @endphp
                    @forelse ($sections as $section)
                        <tr class="table-primary">
                            <td colspan="5" class="fw-bold">
                                <i class="bi bi-building"></i> {{ $section['title'] }}
                                <span class="badge bg-secondary ms-1">{{ $section['users']->count() }}</span>
                            </td>
                        </tr>

                        @foreach ($section['users'] as $user)
                            @php $counter++; @endphp
                            <tr>
                                <td class="text-center">{{ $counter }}</td>
                                <td>
                                    <span class="fw-medium">{{ $user->name }}</span>
                                    @if ($user->hasRole('Super Admin'))
                                        <span class="badge bg-danger ms-1">Super Admin</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse ($user->getRoleNames() as $role)
                                            <span class="badge {{ $role === 'Super Admin' ? 'bg-danger' : 'bg-primary' }}">{{ $role }}</span>
                                        @empty
                                            <span class="text-muted fst-italic">Tiada peranan</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []))
                                            @if (Auth::user()->hasRole('Super Admin'))
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endif
                                        @else
                                            @can('edit-user')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('delete-user')
                                                @if (Auth::user()->id != $user->id)
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="post" class="d-inline" onsubmit="return confirm('Anda pasti mahu memadam pengguna ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Padam">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <strong>Tiada pengguna ditemui!</strong>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection