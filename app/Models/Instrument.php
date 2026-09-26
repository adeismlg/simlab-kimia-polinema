<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = ['kode_alat', 'nama_alat', 'merk', 'lokasi', 'status'];

    public function calibrations(): HasMany
    {
        return $this->hasMany(Calibration::class);
    }

    public function practicumSchedules(): HasMany
    {
        return $this->hasMany(PracticumSchedule::class);
    }

    public function latestCalibration(): HasMany
    {
        return $this->calibrations()->latest('tanggal_kalibrasi');
    }
}
