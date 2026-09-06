<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sektor extends Model
{
    use HasFactory;

    protected $table = 'sektors';

    protected $fillable = [
        'nama',
        'kod',
        'pejabat_pendidikan_id',
        'sort_order',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('nama');
    }

    public function pejabatPendidikan(): BelongsTo
    {
        return $this->belongsTo(PejabatPendidikan::class, 'pejabat_pendidikan_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'sektor_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'sektor_id');
    }
}