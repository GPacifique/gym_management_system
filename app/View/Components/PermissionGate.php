<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PermissionGate extends Component
{
    public array $roles;
    
    /**
     * Create a new component instance.
     */
    public function __construct(string $roles)
    {
        $this->roles = array_map('trim', explode(',', $roles));
    }

    /**
     * Determine if the user has the required roles.
     */
    public function hasPermission(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return auth()->user()->hasAnyRole($this->roles);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.permission-gate');
    }
}
