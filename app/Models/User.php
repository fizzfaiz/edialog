<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pejabat_pendidikan_id',
        'sektor_id',
        'unit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setting(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function pejabatPendidikan(): BelongsTo
    {
        return $this->belongsTo(PejabatPendidikan::class, 'pejabat_pendidikan_id');
    }

    public function sektor(): BelongsTo
    {
        return $this->belongsTo(Sektor::class, 'sektor_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}