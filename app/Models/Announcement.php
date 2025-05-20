<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    // protected $fillable = ['title', 'message', 'category'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeGeneral($query)
    {
        return $query->where('category', 'umum');
    }

    public function scopePersonal($query)
    {
        return $query->where('category', 'personal');
    }
}
