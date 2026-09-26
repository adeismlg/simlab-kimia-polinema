<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calibration extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id', 'tanggal_kalibrasi', 'tanggal_jatuh_tempo',
        'file_sertifikat_kalibrasi', 'status', 'vendor_kalibrasi', 'catatan',
    ];

    protected $casts = [
        'tanggal_kalibrasi' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    /**
     * True jika sudah lewat/dekat (<=30 hari) dari tanggal jatuh tempo.
     */
    public function getPerluPerhatianAttribute(): bool
    {
        return $this->tanggal_jatuh_tempo->diffInDays(now(), false) >= -30;
    }
}
