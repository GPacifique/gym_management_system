<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToGym;

class ClassBooking extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'gym_id',
        'class_id',
        'member_id',
        'user_id',
        'status',
        'booked_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the class this booking is for.
     */
    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    /**
     * Get the member who made this booking.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user who made this booking (if applicable).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to get cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'confirmed' && 
               $this->gymClass->scheduled_at->isFuture();
    }

    /**
     * Cancel this booking.
     */
    public function cancel(string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        
        return $this->save();
    }
}
