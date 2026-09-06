<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:250|unique:users,email,'.$this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required',
            'pejabat_pendidikan_id' => 'nullable|integer|exists:pejabat_pendidikans,id',
            'sektor_id' => 'nullable|integer|exists:sektors,id',
            'unit_id' => 'nullable|integer|exists:units,id',
        ];
    }
}