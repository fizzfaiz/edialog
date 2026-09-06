<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PejabatPendidikan extends Model
{
    use HasFactory;

    protected $table = 'pejabat_pendidikans';

    protected $fillable = [
        'nama',
        'kod',
        'jenis',
        'induk_id',
    ];

    public function induk(): BelongsTo
    {
        return $this->belongsTo(PejabatPendidikan::class, 'induk_id');
    }

    public function anak(): HasMany
    {
        return $this->hasMany(PejabatPendidikan::class, 'induk_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'pejabat_pendidikan_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(DialogPrestasiReport::class, 'pejabat_pendidikan_id');
    }

    public function sektors(): HasMany
    {
        return $this->hasMany(Sektor::class, 'pejabat_pendidikan_id');
    }

    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function isKpm(): bool
    {
        return $this->jenis === 'kpm';
    }

    public function isJpn(): bool
    {
        return $this->jenis === 'jpn';
    }

    public function isPpd(): bool
    {
        return $this->jenis === 'ppd';
    }
}