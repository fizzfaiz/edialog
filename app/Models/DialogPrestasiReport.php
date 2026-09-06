<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DialogPrestasiReport extends Model
{
    use HasFactory;

    protected $table = 'dialog_prestasi_reports';

    protected $fillable = [
        'pejabat_pendidikan_id',
        'sektor_id',
        'unit_id',
        'pengerusi',
        'kategori',
        'tarikh',
        'hari',
        'masa',
        'tempat',
        'dicatat_oleh',
        'jawatan_pencatat',
        'disahkan_oleh',
        'jawatan_pengesah',
    ];

    protected $casts = [
        'tarikh' => 'date',
    ];

    public function getMasaAttribute($value): ?\Carbon\Carbon
    {
        if (empty($value)) {
            return null;
        }
        try {
            return \Carbon\Carbon::createFromFormat('H:i:s', $value);
        } catch (\Exception $e) {
        }
        try {
            return \Carbon\Carbon::createFromFormat('H:i', $value);
        } catch (\Exception $e) {
        }
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function issues(): HasMany
    {
        return $this->hasMany(DialogPrestasiIssue::class, 'report_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(DialogPrestasiAttendance::class, 'report_id');
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