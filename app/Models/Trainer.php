<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToGym;

class Trainer extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = ['gym_id', 'name', 'specialization', 'email', 'phone', 'bio', 'photo_path', 'certifications', 'salary'];

    protected $appends = ['photo_url'];

    /**
     * Get the members assigned to this trainer.
     */
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the classes taught by this trainer.
     */
    public function classes()
    {
        return $this->hasMany(GymClass::class, 'trainer_id');
    }

    /**
     * Get publicly accessible photo URL or a placeholder.
     */
    public function getPhotoUrlAttribute(): string
    {
        if (!empty($this->photo_path)) {
            return asset('storage/' . ltrim($this->photo_path, '/'));
        }
        return asset('images/trainer-placeholder.svg');
    }
}
