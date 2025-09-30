<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenunganAbsensi extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // relasi ke user yang diabsen
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke kepala yang input
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
