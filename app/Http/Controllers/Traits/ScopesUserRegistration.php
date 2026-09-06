<?php

namespace App\Http\Controllers\Traits;

use App\Models\PejabatPendidikan;
use App\Models\Sektor;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;

trait ScopesUserRegistration
{
    /**
     * Determine the current user's registration scope (pejabat / sektor / unit / roles).
     */
    protected function registrationScope(): array
    {
        $user = auth()->user();
        $office = $user?->pejabatPendidikan;

        // Full access: Super Admin or KPM office
        if ($user && ($user->hasRole('Super Admin') || ($office && $office->isKpm()))) {
            return [
                'full' => true,
                'pejabat_ids' => null,
                'sektor_id' => null,
                'unit_id' => null,
                'roles' => Role::pluck('name')->all(),
            ];
        }

        // Sektor Admin (sektor-level user)
        if ($user && $user->hasRole('Sektor Admin')) {
            return [
                'full' => false,
                'pejabat_ids' => $office ? [$office->id] : [],
                'sektor_id' => $user->sektor_id,
                'unit_id' => $user->unit_id,
                'roles' => ['PPD User', 'Sektor Admin'],
            ];
        }

        // PPD Admin
        if ($user && $user->hasRole('PPD Admin')) {
            return [
                'full' => false,
                'pejabat_ids' => $office ? [$office->id] : [],
                'sektor_id' => null,
                'unit_id' => null,
                'roles' => ['PPD Admin', 'PPD User', 'Sektor Admin'],
            ];
        }

        // JPN Admin
        if ($user && $user->hasRole('JPN Admin')) {
            $ids = $office ? $office->anak()->pluck('id')->push($office->id)->all() : [];
            return [
                'full' => false,
                'pejabat_ids' => $ids,
                'sektor_id' => null,
                'unit_id' => null,
                'roles' => ['JPN Admin', 'JPN User', 'PPD Admin', 'PPD User', 'Sektor Admin'],
            ];
        }

        // Fallback: location-based scopes
        if ($user && $user->unit_id) {
            return [
                'full' => false,
                'pejabat_ids' => $office ? [$office->id] : [],
                'sektor_id' => $user->sektor_id,
                'unit_id' => $user->unit_id,
                'roles' => ['PPD User', 'Sektor Admin'],
            ];
        }

        if ($user && $user->sektor_id) {
            return [
                'full' => false,
                'pejabat_ids' => $office ? [$office->id] : [],
                'sektor_id' => $user->sektor_id,
                'unit_id' => null,
                'roles' => ['PPD User', 'Sektor Admin'],
            ];
        }

        if ($office && $office->isPpd()) {
            return [
                'full' => false,
                'pejabat_ids' => [$office->id],
                'sektor_id' => null,
                'unit_id' => null,
                'roles' => ['PPD Admin', 'PPD User', 'Sektor Admin'],
            ];
        }

        if ($office && $office->isJpn()) {
            $ids = $office->anak()->pluck('id')->push($office->id)->all();
            return [
                'full' => false,
                'pejabat_ids' => $ids,
                'sektor_id' => null,
                'unit_id' => null,
                'roles' => ['JPN Admin', 'JPN User', 'PPD Admin', 'PPD User', 'Sektor Admin'],
            ];
        }

        return [
            'full' => false,
            'pejabat_ids' => [],
            'sektor_id' => null,
            'unit_id' => null,
            'roles' => [],
        ];
    }

    protected function availablePejabats(array $scope)
    {
        if ($scope['full']) {
            return PejabatPendidikan::orderBy('jenis')->orderBy('nama')->get();
        }

        return PejabatPendidikan::whereIn('id', $scope['pejabat_ids'])
            ->orderBy('jenis')
            ->orderBy('nama')
            ->get();
    }

    protected function availableRoles(array $scope): array
    {
        return $scope['roles'];
    }

    protected function pejabatInScope(array $scope, $id): bool
    {
        if ($scope['full']) {
            return true;
        }

        return in_array((int) $id, array_map('intval', $scope['pejabat_ids']), true);
    }

    protected function sektorInScope(array $scope, $sektorId): bool
    {
        if ($scope['full']) {
            return true;
        }

        if ($scope['sektor_id']) {
            return (int) $sektorId === (int) $scope['sektor_id'];
        }

        $sektor = Sektor::find($sektorId);

        return $sektor && in_array((int) $sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true);
    }

    protected function unitInScope(array $scope, $unitId): bool
    {
        if ($scope['full']) {
            return true;
        }

        if ($scope['unit_id']) {
            return (int) $unitId === (int) $scope['unit_id'];
        }

        if ($scope['sektor_id']) {
            $unit = Unit::find($unitId);

            return $unit && (int) $unit->sektor_id === (int) $scope['sektor_id'];
        }

        $unit = Unit::with('sektor')->find($unitId);

        return $unit && $unit->sektor && in_array((int) $unit->sektor->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true);
    }

    protected function rolesInScope(array $scope, array $roleNames): bool
    {
        if ($scope['full']) {
            return true;
        }

        foreach ($roleNames as $name) {
            if (!in_array($name, $scope['roles'], true)) {
                return false;
            }
        }

        return true;
    }

    protected function userInScope(array $scope, User $targetUser): bool
    {
        if ($scope['full']) {
            return true;
        }

        if (!$targetUser->pejabat_pendidikan_id) {
            return false;
        }

        if (!in_array((int) $targetUser->pejabat_pendidikan_id, array_map('intval', $scope['pejabat_ids']), true)) {
            return false;
        }

        if ($scope['sektor_id'] && (int) $targetUser->sektor_id !== (int) $scope['sektor_id']) {
            return false;
        }

        if ($scope['unit_id'] && (int) $targetUser->unit_id !== (int) $scope['unit_id']) {
            return false;
        }

        return true;
    }

    protected function authorizeRegistrationScope(array $scope, $request): void
    {
        $pejabatId = $request->input('pejabat_pendidikan_id');
        $sektorId = $request->input('sektor_id');
        $unitId = $request->input('unit_id');
        $roles = (array) $request->input('roles', []);

        if ($pejabatId && !$this->pejabatInScope($scope, $pejabatId)) {
            abort(403, 'Pejabat di luar skop anda.');
        }

        if ($sektorId && !$this->sektorInScope($scope, $sektorId)) {
            abort(403, 'Sektor di luar skop anda.');
        }

        if ($unitId && !$this->unitInScope($scope, $unitId)) {
            abort(403, 'Unit di luar skop anda.');
        }

        if (!$this->rolesInScope($scope, $roles)) {
            abort(403, 'Peranan di luar skop anda.');
        }
    }
}
