<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'assignment_date' => 'date',
        'start_assignment_time' => 'datetime:H:i',
        'end_assignment_time' => 'datetime:H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }
}
