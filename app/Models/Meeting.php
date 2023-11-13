<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_start_time' => 'datetime:H:i', // Ubah menjadi datetime
        'meeting_end_time' => 'datetime:H:i', // Ubah menjadi datetime
    ];

    public function participants()
    {
         return $this->belongsToMany(User::class,'meeting_participant','meeting_id','participant_id');
    }
}
