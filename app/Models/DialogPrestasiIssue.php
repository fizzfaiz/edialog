<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DialogPrestasiIssue extends Model
{
    use HasFactory;

    protected $table = 'dialog_prestasi_issues';

    protected $fillable = [
        'report_id',
        'bil',
        'isu',
        'fokus',
        'tindakan',
        'sektor_pegawai',
        'tagged_sektor_id',
        'tagged_unit_id',
        'jawapan',
        'status',
        'answered_by',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(DialogPrestasiReport::class, 'report_id');
    }

    public function taggedSektor(): BelongsTo
    {
        return $this->belongsTo(Sektor::class, 'tagged_sektor_id');
    }

    public function taggedUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'tagged_unit_id');
    }

    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }
}