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
        'is_active' => 'boolean',
    ];



    public function getDurasiAttribute()
    {
        return $this->mulai_berlaku->diffInDays($this->berakhir_berlaku) . ' hari';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::retrieved(function ($sp) {
            if ($sp->is_active && $sp->berakhir_berlaku->isPast()) {
                $sp->update(['is_active' => false]);
            }
        });
    }

    public function getSisaDurasiAttribute()
    {
        if ($this->berakhir_berlaku->isPast()) {
            return 'Kadaluarsa';
        }

        $diff = now()->diff($this->berakhir_berlaku);
        $parts = [];

        if ($diff->m > 0) $parts[] = $diff->m . ' bulan';
        if ($diff->d > 0) $parts[] = $diff->d . ' hari';

        return implode(' ', $parts);
    }
}
