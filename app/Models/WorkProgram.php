<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProgram extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // User yang upload
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Unit kerja
    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (optional tapi recommended)
    |--------------------------------------------------------------------------
    */

    // URL file langsung
    public function getFileUrlAttribute()
    {
        return asset('WorkPrograms/' . $this->file_path);
    }
}
