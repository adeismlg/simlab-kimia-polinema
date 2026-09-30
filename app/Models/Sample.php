<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sampel', 'user_id', 'nama_sampel', 'deskripsi',
        'file_dokumen', 'status', 'verified_by', 'verified_at', 'catatan',
        'lokasi_penyimpanan', 'label_dicetak_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'label_dicetak_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'sample_parameters')
            ->withPivot(['hasil', 'satuan_hasil', 'harga_saat_daftar'])
            ->withTimestamps();
    }

    public function testResult(): HasOne
    {
        return $this->hasOne(TestResult::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    /**
     * Total biaya = jumlah harga semua parameter (snapshot saat daftar).
     */
    public function getTotalBiayaAttribute(): float
    {
        return $this->parameters->sum('pivot.harga_saat_daftar');
    }
}
