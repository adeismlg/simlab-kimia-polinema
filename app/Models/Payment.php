<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_id', 'payable_type', 'jumlah', 'ppn', 'total',
        'metode', 'status', 'bukti_bayar', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'ppn' => 'decimal:2',
        'total' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Hitung PPN 11% untuk pelanggan eksternal, 0 untuk internal.
     */
    public static function hitungTotal(float $jumlah, string $tipeUser): array
    {
        $ppn = $tipeUser === 'eksternal' ? round($jumlah * 0.11, 2) : 0;

        return [
            'jumlah' => $jumlah,
            'ppn' => $ppn,
            'total' => $jumlah + $ppn,
        ];
    }
}
