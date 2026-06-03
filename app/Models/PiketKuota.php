<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketKuota extends Model
{
    use HasFactory;

    protected $table = 'piket_kuotas';

    protected $fillable = [
        'unit_kerja_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_kuota',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'dibuat_oleh'
        );
    }
}
