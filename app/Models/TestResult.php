<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id', 'file_sertifikat', 'status_approval',
        'approved_by', 'approved_at', 'catatan_approval',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
