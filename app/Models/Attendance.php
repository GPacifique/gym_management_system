<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToGym;

class Attendance extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'gym_id',
        'member_id',
        'class_id',
        'check_in_time',
        'check_out_time',
        'notes'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime'
    ];

    /**
     * Get the member that owns the attendance record.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the class for this attendance record.
     */
    public function class()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    /**
     * Get the duration of the visit.
     */
    public function getDurationAttribute()
    {
        if (!$this->check_out_time) {
            return null;
        }

        return $this->check_in_time->diffForHumans($this->check_out_time, true);
    }

    /**
     * Check if the attendance is active (not checked out).
     */
    public function isActive()
    {
        return $this->check_out_time === null;
    }
}
