<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticumSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_praktikum', 'dosen_id', 'instrument_id',
        'tanggal', 'jam_mulai', 'jam_selesai', 'kapasitas', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(PracticumBooking::class, 'schedule_id');
    }

    public function getSisaKuotaAttribute(): int
    {
        return $this->kapasitas - $this->bookings()->where('status', 'disetujui')->count();
    }
}
