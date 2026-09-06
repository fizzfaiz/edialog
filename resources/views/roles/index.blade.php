@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <span><i class="bi bi-shield-lock me-2"></i>Pengurusan Peranan (Roles)</span>
        @can('create-role')
            <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Tambah Peranan</a>
        @endcan
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:5%">#</th>
                        <th style="width:15%">Peranan</th>
                        <th>Kebenaran (Permissions)</th>
                        <th style="width:15%">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-bold {{ $role->name === 'Super Admin' ? 'text-danger' : '' }}">
                                {{ $role->name }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse ($role->permissions as $permission)
                                    <span class="badge bg-primary">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-muted fst-italic">Tiada permission</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if ($role->name !== 'Super Admin')
                                    @can('edit-role')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan

                                    @can('delete-role')
                                        @if (!auth()->user()->hasRole($role->name))
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="post" class="d-inline" onsubmit="return confirm('Anda pasti mahu memadam peranan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Padam">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                @else
                                    <span class="badge bg-secondary">Terkunci</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <strong>Tiada peranan ditemui!</strong>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection