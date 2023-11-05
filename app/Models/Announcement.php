<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    // protected $fillable = ['title', 'message', 'category'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
