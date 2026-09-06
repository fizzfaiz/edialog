<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DialogPrestasiAttendance extends Model
{
    use HasFactory;

    protected $table = 'dialog_prestasi_attendances';

    protected $fillable = [
        'report_id',
        'nama',
        'jawatan',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(DialogPrestasiReport::class, 'report_id');
    }
}
