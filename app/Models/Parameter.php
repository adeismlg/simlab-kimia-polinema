<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_parameter', 'satuan', 'metode_uji',
        'harga_internal', 'harga_eksternal', 'deskripsi', 'aktif',
    ];

    protected $casts = [
        'harga_internal' => 'decimal:2',
        'harga_eksternal' => 'decimal:2',
        'aktif' => 'boolean',
    ];

    public function samples(): BelongsToMany
    {
        return $this->belongsToMany(Sample::class, 'sample_parameters')
            ->withPivot(['hasil', 'satuan_hasil', 'harga_saat_daftar'])
            ->withTimestamps();
    }

    /**
     * Ambil harga sesuai tipe user (internal/eksternal).
     */
    public function hargaUntuk(User $user): float
    {
        return $user->tipe === 'eksternal' ? (float) $this->harga_eksternal : (float) $this->harga_internal;
    }
}
