<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
