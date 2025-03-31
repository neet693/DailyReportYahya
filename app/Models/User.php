<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'address',
        'gender',
        'martial_status',
        'birth_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'datetime',
        'password' => 'hashed',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_user_id');
    }
    public function announcements()
    {
        return $this->belongsToMany(Announcement::class);
    }

    public function jobdesk()
    {
        return $this->hasOne(JobDesk::class, 'jobdesk_user_id');
    }

    public function agendas()
    {
        return $this->belongsToMany(Agenda::class, 'agenda_executor', 'executor_id', 'agenda_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'user_id');
    }

    public function employmentDetail()
    {
        return $this->hasOne(EmploymentDetail::class);
    }

    public function educationHistories()
    {
        return $this->hasMany(EducationHistory::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
