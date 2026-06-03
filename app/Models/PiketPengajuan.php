<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketPengajuan extends Model
{
    use HasFactory;
    protected $table = 'piket_pengajuans';

    protected $fillable = [
        'user_id',
        'tanggal_piket',
        'status',
        'catatan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_piket' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
