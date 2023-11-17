<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;
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
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        $weeks = $startDate->diffInWeeks($endDate);
        $months = $startDate->diffInMonths($endDate);
        $years = $startDate->diffInYears($endDate);

        $period = '';
        if ($years > 0) {
            $period .= $years . ' tahun ';
        } elseif ($months > 0) {
            $period .= $months . ' bulan ';
        } elseif ($weeks > 0) {
            $period .= $weeks . ' minggu ';
        }

        return trim($period);
    }
}
