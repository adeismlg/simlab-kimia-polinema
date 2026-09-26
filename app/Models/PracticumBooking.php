<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PracticumBooking extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id', 'user_id', 'status', 'catatan'];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(PracticumSchedule::class, 'schedule_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
