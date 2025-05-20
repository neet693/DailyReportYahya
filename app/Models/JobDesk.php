<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDesk extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'jobdesk_user_id');
    }
}
