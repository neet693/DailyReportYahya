<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratPeringatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'mulai_berlaku' => 'date',
        'berakhir_berlaku' => 'date',
    ];


    public function getDurasiAttribute()
    {
        return $this->mulai_berlaku->diffInDays($this->berakhir_berlaku) . ' hari';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
