<?php

use Illuminate\Support\Str;
use Carbon\Carbon;

if (!function_exists('app_name')) {
    function app_name()
    {
        return config('app.name', 'GymMS');
    }
}

if (!function_exists('hasRole')) {
    function hasRole($roles)
    {
        if (!auth()->check()) return false;

        $userRole = auth()->user()->role;

        return in_array($userRole, (array) $roles);
    }
}

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

if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'RWF')
    {
        // RWF typically doesn't use decimals
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

if (!function_exists('is_active_route')) {
    function is_active_route($route)
    {
        return request()->routeIs($route) ? 'active bg-primary text-white' : '';
    }
}

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
        // Example: 0785221105 → 078 522 1105
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1 $2 $3', $phone);
    }
}

if (!function_exists('status_color')) {
    function status_color($status)
    {
        return match ($status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
            default => 'primary',
        };
    }
}