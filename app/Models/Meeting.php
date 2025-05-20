<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_start_time' => 'datetime:H:i', // Ubah menjadi datetime
        'meeting_end_time' => 'datetime:H:i', // Ubah menjadi datetime
    ];


    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }


    public function participants()
    {
        return $this->belongsToMany(User::class, 'meeting_participant', 'meeting_id', 'participant_id');
    }
}
