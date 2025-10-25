<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the gym this user belongs to.
     */
    public function gym()
    {
        return $this->belongsTo(\App\Models\Gym::class);
    }

    public function defaultGym()
    {
        return $this->belongsTo(\App\Models\Gym::class, 'default_gym_id');
    }

    public function gyms()
    {
        return $this->belongsToMany(\App\Models\Gym::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Check if user has access to a specific gym.
     */
    public function hasGymAccess(int $gymId): bool
    {
        // Users belong to a specific gym via gym_id
        if ($this->gym_id === $gymId) {
            return true;
        }
        // Also check many-to-many relationship for multi-gym access
        return $this->gyms()->where('gyms.id', $gymId)->exists();
    }

    /**
     * Get user's role for a specific gym.
     */
    public function roleInGym(int $gymId): ?string
    {
        // Check primary gym assignment
        if ($this->gym_id === $gymId) {
            return $this->role;
        }
        // Check many-to-many pivot table
        $pivot = $this->gyms()->where('gyms.id', $gymId)->first()?->pivot;
        return $pivot?->role ?? null;
    }

    /**
     * Check if user has a specific role in a gym.
     */
    public function hasRoleInGym(string $role, int $gymId): bool
    {
        return $this->roleInGym($gymId) === $role;
    }

    /**
     * Check if user has any of the given roles in a gym.
     */
    public function hasAnyRoleInGym(array $roles, int $gymId): bool
    {
        return in_array($this->roleInGym($gymId), $roles);
    }

    /**
     * Canonical list of application roles.
     */
    public const ROLES = ['admin', 'manager', 'receptionist', 'trainer', 'member'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gym_id',
        'default_gym_id',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is receptionist.
     */
    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    /**
     * Check if user is trainer.
     */
    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    /**
     * Check if user is member.
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
