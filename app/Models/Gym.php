<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'opening_hours',
        'logo',
        'timezone',
        'owner_user_id',
        'is_verified',
        'email_verified_at',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'subscription_tier',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get all users directly assigned to this gym via gym_id.
     */
    public function assignedUsers()
    {
        return $this->hasMany(User::class, 'gym_id');
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function branches()
    {
        return $this->hasMany(\App\Models\Branch::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if gym email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark gym email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Check if gym is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if gym is pending approval
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if gym is rejected
     */
    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    /**
     * Check if gym is on trial period
     */
    public function isOnTrial(): bool
    {
        return $this->subscription_tier === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    /**
     * Check if trial has expired
     */
    public function trialExpired(): bool
    {
        return $this->subscription_tier === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isPast();
    }
}
