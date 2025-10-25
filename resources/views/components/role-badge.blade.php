@props(['role'])

@php
    $colors = [
        'admin' => 'danger',
        'manager' => 'primary',
        'receptionist' => 'info',
        'trainer' => 'success',
        'member' => 'secondary',
    ];
    
    $icons = [
        'admin' => 'shield-fill-check',
        'manager' => 'briefcase-fill',
        'receptionist' => 'person-badge-fill',
        'trainer' => 'heart-pulse-fill',
        'member' => 'person-fill',
    ];
    
    $color = $colors[$role] ?? 'secondary';
    $icon = $icons[$role] ?? 'person';
@endphp

<span {{ $attributes->merge(['class' => "badge bg-{$color}"]) }}>
    <i class="bi bi-{{ $icon }} me-1"></i>
    {{ ucfirst($role) }}
</span>
