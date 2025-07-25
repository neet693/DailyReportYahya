<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    //Constant Helper
    public const ROLE_ADMIN = 'admin';
    public const ROLE_KEPALA_UNIT = 'kepala';
    public const ROLE_PEGAWAI = 'pegawai';
    public const ROLE_HRD = 'hrd';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKepalaUnit()
    {
        return $this->role === self::ROLE_KEPALA_UNIT;
    }

    public function isHRD()
    {
        return $this->role === self::ROLE_HRD;
    }

    public function isPegawai()
    {
        return $this->role === self::ROLE_PEGAWAI;
    }

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

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_participant', 'participant_id', 'meeting_id');
    }

    // Di User.php
    public function units()
    {
        return $this->belongsToMany(UnitKerja::class, 'unit_user', 'user_id', 'unit_id');
    }

    //Message
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Status SP
    public function statusPeringatan()
    {
        return $this->hasMany(SuratPeringatan::class);
    }

    // Can view SP
    public function canViewSP(User $targetUser)
    {
        // Admin atau HRD boleh lihat semua
        if (in_array($this->role, ['admin', 'hrd'])) {
            return true;
        }

        // Kepala unit yang satu unit dengan target user
        if ($this->role === 'kepala' && $this->units->pluck('id')->contains($targetUser->employmentDetail->unit_id)) {
            return true;
        }

        // Dirinya sendiri
        if ($this->id === $targetUser->id) {
            return true;
        }

        return false;
    }

    public function statusPeringatanAktif()
    {
        return $this->hasMany(SuratPeringatan::class)
            ->where('berakhir_berlaku', '>=', Carbon::today());
    }
}
