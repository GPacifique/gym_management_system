<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToGym;

class GymClass extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    protected $fillable = [
        'gym_id',
        'class_name',
        'trainer_id',
        'location',
        'scheduled_at',
        'capacity'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    /**
     * Get the trainer for this class.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get the attendances for this class.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    /**
     * Get the bookings for this class.
     */
    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_id');
    }

    /**
     * Get confirmed bookings for this class.
     */
    public function confirmedBookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_id')->where('status', 'confirmed');
    }

    /**
     * Get the formatted name attribute.
     */
    public function getNameAttribute()
    {
        return $this->class_name;
    }

    /**
     * Check if the class is full.
     */
    public function isFull()
    {
        $currentBookings = $this->confirmedBookings()->count();
        return $currentBookings >= $this->capacity;
    }

    /**
     * Get available spots.
     */
    public function getAvailableSpotsAttribute()
    {
        return max(0, $this->capacity - $this->confirmedBookings()->count());
    }

    /**
     * Check if class can be booked.
     */
    public function canBeBooked(): bool
    {
        return !$this->isFull() && $this->scheduled_at->isFuture();
    }
}
