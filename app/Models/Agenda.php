<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agenda extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function executors()
    {
        return $this->belongsToMany(User::class, 'agenda_executor', 'agenda_id', 'executor_id');
    }

    protected $dates = ['start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    // Accessor untuk menghitung periode
    public function getPeriodAttribute()
    {
        return $this->calculatePeriod();
    }

    // Fungsi untuk menghitung periode
    private function calculatePeriod()
    {
        if (!$this->start_date || !$this->end_date) {
            return '-';
        }

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        $years = $startDate->diffInYears($endDate);
        $months = $startDate->diffInMonths($endDate) % 12;
        $weeks = $startDate->diffInWeeks($endDate) % 4;

        $period = '';
        if ($years > 0) {
            $period .= $years . ' tahun ';
        }
        if ($months > 0) {
            $period .= $months . ' bulan ';
        }
        if ($weeks > 0) {
            $period .= $weeks . ' minggu ';
        }

        // Fallback kalau semua 0
        if ($period === '') {
            $days = $startDate->diffInDays($endDate);
            $period = $days . ' hari';
        }

        return trim($period);
    }


    public function logs()
    {
        return $this->hasMany(LogAgenda::class);
    }
}
