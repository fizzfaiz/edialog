@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <i class="bi bi-shield-plus me-2"></i>Tambah Peranan Baru
                </div>
                <div class="float-end">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">&larr; Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="post">
                    @csrf

                    <div class="mb-4 row">
                        <label for="name" class="col-md-3 col-form-label text-md-end text-start fw-bold">Nama Peranan</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Admin, Editor, ...">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label text-md-end text-start fw-bold">Kebenaran (Permissions)</label>
                        <div class="col-md-9">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="bi bi-info-circle"></i> Pilih modul yang boleh diakses oleh peranan ini. Tanda <strong class="text-success">&#10003;</strong> pada kotak untuk memberi kebenaran.
                            </div>

                            @php
                                /**
                                 * AUTO-DETECT MODULES & GROUP PERMISSIONS
                                 *
                                 * The system reads ALL permissions from the database,
                                 * extracts the {action}-{module} parts automatically,
                                 * and groups them by module. No hardcoded mapping needed.
                                 *
                                 * To add a new module, just:
                                 *   1. Add permissions to PermissionSeeder (firstOrCreate)
                                 *   2. Optionally add a label to config/permission_modules.php
                                 *   3. Run: php artisan db:seed --class=PermissionSeeder
                                 */

                                $configActions = config('permission_modules.action_labels', []);
                                $configModules = config('permission_modules.module_labels', []);
                                $configIcons   = config('permission_modules.module_icons', []);
                                $defaultIcon   = config('permission_modules.default_icon', 'bi-folder2-open');

                                $groupedPermissions = [];

                                foreach ($permissions as $perm) {
                                    $name = $perm->name;

                                    // Parse permission name: extract action prefix and module suffix
                                    // Examples:
                                    //   "view-dialog-prestasi"       → action: "view",   module: "dialog-prestasi"
                                    //   "create-user"        → action: "create", module: "user"
                                    //   "manage-settings"    → action: "manage", module: "manage-settings"
                                    //   "delete-dialog-prestasi"      → action: "delete", module: "dialog-prestasi"

                                    $module = null;
                                    $action = null;

                                    // Try common action prefixes (longer prefixes first to avoid partial match)
                                    $knownActions = array_keys($configActions);
                                    // Sort by length descending so "manage" matches before "edit" etc.
                                    usort($knownActions, function($a, $b) { return strlen($b) - strlen($a); });

                                    foreach ($knownActions as $act) {
                                        if (str_starts_with($name, $act . '-')) {
                                            $action = $act;
                                            $module = substr($name, strlen($act) + 1); // everything after "{action}-"
                                            break;
                                        }
                                    }

                                    // Fallback: use the full name as module if no known action prefix found
                                    if ($module === null) {
                                        $module = $name;
                                        $action = $name;
                                    }

                                    // Get display labels
                                    $actionLabel = $configActions[$action] ?? ucfirst($action);
                                    $moduleLabel = $configModules[$module] ?? ucfirst(str_replace('-', ' ', $module));
                                    $moduleIcon  = $configIcons[$module] ?? $defaultIcon;

                                    $groupedPermissions[$module][] = [
                                        'perm'        => $perm,
                                        'label'       => $actionLabel,
                                        'moduleLabel' => $moduleLabel,
                                        'moduleIcon'  => $moduleIcon,
                                    ];
                                }

                                // Sort modules by label for consistent display
                                ksort($groupedPermissions);
                            @endphp

                            @foreach ($groupedPermissions as $module => $perms)
                                @php
                                    $first = $perms[0] ?? null;
                                    $moduleLabel = $first['moduleLabel'] ?? ucfirst(str_replace('-', ' ', $module));
                                    $moduleIcon  = $first['moduleIcon'] ?? 'bi-folder2-open';
                                @endphp
                                <div class="card mb-3 border">
                                    <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                                        <span class="fw-bold text-dark">
                                            <i class="bi {{ $moduleIcon }} me-2"></i>{{ $moduleLabel }}
                                        </span>
                                        <div>
                                            <a href="#" class="btn btn-sm btn-outline-primary select-all-module" data-module="{{ $module }}">
                                                <i class="bi bi-check-all"></i> Pilih Semua
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-secondary deselect-all-module" data-module="{{ $module }}">
                                                <i class="bi bi-x-circle"></i> Kosongkan
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="row">
                                            @foreach ($perms as $item)
                                                @php $perm = $item['perm']; $actionLabel = $item['label']; @endphp
                                                <div class="col-md-3 col-lg-2">
                                                    <div class="form-check permission-check-{{ $module }}">
                                                        <input class="form-check-input module-{{ $module }}-check" type="checkbox"
                                                               name="permissions[]" value="{{ $perm->id }}"
                                                               id="perm_{{ $perm->id }}"
                                                               {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                            {{ $actionLabel }}
                                                            <small class="text-muted d-block">{{ $perm->name }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Simpan Peranan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.select-all-module').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var module = this.getAttribute('data-module');
            var checks = document.querySelectorAll('.module-' + module + '-check');
            checks.forEach(function(cb) { cb.checked = true; });
        });
    });

    document.querySelectorAll('.deselect-all-module').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var module = this.getAttribute('data-module');
            var checks = document.querySelectorAll('.module-' + module + '-check');
            checks.forEach(function(cb) { cb.checked = false; });
        });
    });
</script>
@endpush

@endsection