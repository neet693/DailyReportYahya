<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAgenda extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
}
