<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['task_date' => 'date', 'task_start_time' => 'datetime:H:i', 'task_end_time' => 'datetime:H:i'];

    public function user()
    {
        return $this->belongsTo(User::class, 'task_user_id');
    }

    public function taskExcerpt($length = 30)
    {
        return strlen($this->description) > $length ? substr($this->description, 0, $length) . '...' : $this->description;
    }

    public function scopeTodayOrPending($query, $today)
    {
        return $query->whereDate('task_date', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('progres', 0)
                    ->whereDate('task_date', '<', $today);
            });
    }
}
