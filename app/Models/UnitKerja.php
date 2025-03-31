<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Relasi ke EmploymentDetail
    public function employmentDetails()
    {
        return $this->hasMany(EmploymentDetail::class);
    }
}
