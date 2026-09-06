<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';

    protected $fillable = [
        'nama',
        'kod',
        'sektor_id',
    ];

    public function sektor(): BelongsTo
    {
        return $this->belongsTo(Sektor::class, 'sektor_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_id');
    }
}