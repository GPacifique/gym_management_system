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
        $currentAttendees = $this->attendances()
            ->whereDate('check_in_time', $this->scheduled_at->toDateString())
            ->count();

        return $currentAttendees >= $this->capacity;
    }
}
