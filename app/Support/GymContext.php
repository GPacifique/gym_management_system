<?php

namespace App\Support;

use App\Models\Gym;
use Illuminate\Support\Facades\Auth;

class GymContext
{
    protected static ?int $gymId = null;

    public static function set(?int $gymId): void
    {
        self::$gymId = $gymId;
    }

    public static function id(): ?int
    {
        if (self::$gymId) return self::$gymId;
        $user = Auth::user();
        return $user?->default_gym_id;
    }

    public static function current(): ?Gym
    {
        $id = self::id();
        return $id ? Gym::find($id) : null;
    }
}
