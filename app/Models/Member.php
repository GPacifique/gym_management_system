<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToGym;

class Member extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'gym_id', 'first_name', 'last_name', 'email', 'phone', 'photo_path', 'chip_id', 'dob', 'gender',
        'address', 'join_date', 'trainer_id'
    ];

    protected $appends = ['name', 'photo_url'];

    /**
     * Get the member's full name.
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function trainer() {
        return $this->belongsTo(Trainer::class);
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function workoutPlans() {
        return $this->hasMany(WorkoutPlan::class);
    }

    public function classBookings() {
        return $this->hasMany(ClassBooking::class);
    }

    public function confirmedBookings() {
        return $this->hasMany(ClassBooking::class)->where('status', 'confirmed');
    }

    /**
     * Get publicly accessible photo URL or a placeholder.
     */
    public function getPhotoUrlAttribute(): string
    {
        if (!empty($this->photo_path)) {
            // Use asset('storage/...') to avoid dependency on Storage::url at runtime/static analysis
            return asset('storage/' . ltrim($this->photo_path, '/'));
        }
        return asset('images/member-placeholder.svg');
    }

}
