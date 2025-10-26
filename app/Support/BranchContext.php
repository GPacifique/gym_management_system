<?php

namespace App\Support;

use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

class BranchContext
{
    protected static ?int $branchId = null;

    public static function set(?int $branchId): void
    {
        self::$branchId = $branchId;
    }

    public static function id(): ?int
    {
        if (self::$branchId) return self::$branchId;
        return session('branch_id');
    }

    public static function current(): ?Branch
    {
        $id = self::id();
        return $id ? Branch::find($id) : null;
    }
}
