<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDialogPrestasiReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pejabat_pendidikan_id' => 'nullable|integer|exists:pejabat_pendidikans,id',
            'sektor_id' => 'nullable|integer|exists:sektors,id',
            'unit_id' => 'nullable|integer|exists:units,id',
            'pengerusi' => 'required|string|max:255',
            'tarikh' => 'required|date',
            'hari' => 'required|string|max:50',
            'masa' => 'required',
            'tempat' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'dicatat_oleh' => 'required|string|max:255',
            'jawatan_pencatat' => 'required|string|max:255',
            'disahkan_oleh' => 'required|string|max:255',
            'jawatan_pengesah' => 'required|string|max:255',
            'attendances' => 'nullable|array',
            'attendances.*.nama' => 'required|string|max:255',
            'attendances.*.jawatan' => 'required|string|max:255',
            'issues' => 'nullable|array',
            'issues.*.isu' => 'required|string',
            'issues.*.fokus' => 'nullable|string',
            'issues.*.tindakan' => 'required|string',
            'issues.*.sektor_pegawai' => 'nullable|string|max:255',
            'issues.*.tagged_sektor_id' => 'nullable|integer|exists:sektors,id',
            'issues.*.tagged_unit_id' => 'nullable|integer|exists:units,id',
        ];
    }
}