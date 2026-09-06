<?php

namespace App\Policies;

use App\Models\DialogPrestasiReport;
use App\Models\User;

class DialogPrestasiReportPolicy
{
    /**
     * Determine whether the user can view any models.
     * KPM: all reports
     * JPN: reports from their subordinate PPDs
     * PPD: only their own reports
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DialogPrestasiReport $dialogPrestasiReport): bool
    {
        $office = $user->pejabatPendidikan;
        if (!$office) {
            return false; // No office assigned, no access
        }

        // KPM: can view all
        if ($office->isKpm()) {
            return true;
        }

        // JPN: can view if report belongs to one of their PPDs or the JPN office itself
        if ($office->isJpn()) {
            $ppdIds = $office->anak()->pluck('id')->toArray();
            $ppdIds[] = $office->id;
            return in_array((int) $dialogPrestasiReport->pejabat_pendidikan_id, array_map('intval', $ppdIds), true);
        }

        // PPD: can only view their own reports
        if ($office->isPpd()) {
            return $dialogPrestasiReport->pejabat_pendidikan_id === $office->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission(['create-dialog-prestasi']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DialogPrestasiReport $dialogPrestasiReport): bool
    {
        $office = $user->pejabatPendidikan;
        if (!$office) {
            return false;
        }

        // KPM: can edit all
        if ($office->isKpm()) {
            return true;
        }

        // JPN: can edit if report belongs to one of their PPDs or the JPN office itself
        if ($office->isJpn()) {
            $ppdIds = $office->anak()->pluck('id')->toArray();
            $ppdIds[] = $office->id;
            return in_array((int) $dialogPrestasiReport->pejabat_pendidikan_id, array_map('intval', $ppdIds), true);
        }

        // PPD: can only edit their own reports
        if ($office->isPpd()) {
            return $dialogPrestasiReport->pejabat_pendidikan_id === $office->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only KPM or Super Admin can delete reports.
     */
    public function delete(User $user, DialogPrestasiReport $dialogPrestasiReport): bool
    {
        // Super Admin always can
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        $office = $user->pejabatPendidikan;
        if (!$office) {
            return false;
        }

        // Only KPM can delete
        if ($office->isKpm()) {
            return true;
        }

        return false;
    }
}