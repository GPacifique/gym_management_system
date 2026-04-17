<?php

use Illuminate\Support\Str;
use Carbon\Carbon;

if (!function_exists('app_name')) {
    function app_name()
    {
        return config('app.name', 'GymMS');
    }
}

/*
|--------------------------------------------------------------------------
| TENANT (GYM) CONTEXT
|--------------------------------------------------------------------------
*/

if (!function_exists('current_gym')) {
    function current_gym()
    {
        return session('gym_id') ?? auth()->user()?->default_gym_id;
    }
}

if (!function_exists('current_gym_model')) {
    function current_gym_model()
    {
        $gymId = current_gym();
        return $gymId ? \App\Models\Gym::find($gymId) : null;
    }
}

/*
|--------------------------------------------------------------------------
| ROLE HELPERS (MULTI-TENANT AWARE)
|--------------------------------------------------------------------------
*/

if (!function_exists('hasRole')) {
    function hasRole($roles)
    {
        if (!auth()->check()) return false;

        $user = auth()->user();

        // Super admin bypass
        if ($user->isSuperAdmin()) return true;

        $gymId = current_gym();
        if (!$gymId) return false;

        $role = $user->roleInGym($gymId);

        return in_array($role, (array) $roles);
    }
}

if (!function_exists('hasAnyRole')) {
    function hasAnyRole(array $roles)
    {
        return hasRole($roles);
    }
}

if (!function_exists('hasExactRole')) {
    function hasExactRole(string $role)
    {
        if (!auth()->check()) return false;

        $user = auth()->user();

        if ($user->isSuperAdmin()) return true;

        return $user->roleInGym(current_gym()) === $role;
    }
}

/*
|--------------------------------------------------------------------------
| ID GENERATORS
|--------------------------------------------------------------------------
*/

if (!function_exists('generate_member_id')) {
    function generate_member_id()
    {
        return 'MEM-' . strtoupper(Str::random(5)) . '-' . date('His');
    }
}

if (!function_exists('generate_invoice_number')) {
    function generate_invoice_number()
    {
        return 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
    }
}

/*
|--------------------------------------------------------------------------
| FORMATTERS
|--------------------------------------------------------------------------
*/

if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'RWF')
    {
        return $currency . ' ' . number_format($amount, 0);
    }
}

if (!function_exists('format_date')) {
    function format_date($date)
    {
        return $date ? Carbon::parse($date)->format('d M Y') : '-';
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($date)
    {
        return $date ? Carbon::parse($date)->format('d M Y, H:i') : '-';
    }
}

if (!function_exists('calculate_age')) {
    function calculate_age($dob)
    {
        return $dob ? Carbon::parse($dob)->age : null;
    }
}

if (!function_exists('truncate_text')) {
    function truncate_text($text, $length = 50)
    {
        return Str::limit($text, $length);
    }
}

if (!function_exists('phone_format')) {
    function phone_format($phone)
    {
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1 $2 $3', $phone);
    }
}

/*
|--------------------------------------------------------------------------
| UI HELPERS (BOOTSTRAP FRIENDLY)
|--------------------------------------------------------------------------
*/

if (!function_exists('membership_status_badge')) {
    function membership_status_badge($status)
    {
        return match ($status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'expired' => '<span class="badge bg-danger">Expired</span>',
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}

if (!function_exists('status_badge')) {
    function status_badge($status)
    {
        $color = match ($status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
            default => 'primary',
        };

        return "<span class=\"badge bg-{$color}\">" . ucfirst($status) . "</span>";
    }
}

if (!function_exists('is_active_route')) {
    function is_active_route($route)
    {
        return request()->routeIs($route) ? 'active bg-primary text-white' : '';
    }
}

/*
|--------------------------------------------------------------------------
| GREETING
|--------------------------------------------------------------------------
*/

if (!function_exists('greeting')) {
    function greeting()
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 12 => 'Good Morning',
            $hour < 17 => 'Good Afternoon',
            default => 'Good Evening',
        };
    }
}

/*
|--------------------------------------------------------------------------
| TENANT-SAFE COUNTS (AUTO SCOPED)
|--------------------------------------------------------------------------
*/

if (!function_exists('tenant_count')) {
    function tenant_count($modelClass)
    {
        return $modelClass::count();
    }
}