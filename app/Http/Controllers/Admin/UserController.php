<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\UserCreatedNotification;
use App\Notifications\PasswordResetByAdminNotification;
use App\Notifications\PasswordChangedNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('q')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = User::ROLES;

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = User::ROLES;
        $gyms = \App\Models\Gym::orderBy('name')->get();
        // Current assignments: [gym_id => role]
        $assigned = $user->gyms()->pluck('gym_user.role', 'gyms.id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'gyms', 'assigned'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = User::ROLES;
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
            'gym_id' => ['nullable', 'integer', 'exists:gyms,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $password = $validated['password'] ?? Str::random(12);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'gym_id' => $validated['gym_id'] ?? \Illuminate\Support\Facades\Auth::user()->gym_id ?? null,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // Notify the user
        $user->notify(new UserCreatedNotification(empty($validated['password']), empty($validated['password']) ? $password : null));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully. Temporary password: ' . (isset($validated['password']) ? 'set by admin' : $password));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
            'gym_id' => ['nullable', 'integer', 'exists:gyms,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // Gym assignment inputs
            'gyms' => ['nullable', 'array'],
            'gyms.*' => ['integer', 'exists:gyms,id'],
            'gym_roles' => ['nullable', 'array'],
            'gym_roles.*' => ['nullable', 'in:' . implode(',', User::ROLES)],
            'default_gym_id' => ['nullable', 'integer', 'exists:gyms,id'],
        ]);

        // Prevent locking yourself out: the last admin cannot be demoted
        if ($user->id === $request->user()->id && $validated['role'] !== 'admin') {
            $otherAdmins = User::where('id', '!=', $user->id)->where('role', 'admin')->count();
            if ($otherAdmins === 0) {
                return back()->withErrors(['role' => 'You cannot remove your own admin role if you are the only admin.'])->withInput();
            }
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];
        
        // Update gym_id if provided
        if (isset($validated['gym_id'])) {
            $update['gym_id'] = $validated['gym_id'];
        }
        
        $passwordChanged = !empty($validated['password']);
        if ($passwordChanged) {
            $update['password'] = Hash::make($validated['password']);
        }

        $user->update($update);

        // Sync gym assignments and pivot roles
        $selectedGyms = collect($validated['gyms'] ?? [])->unique()->values();
        $gymRoles = collect($validated['gym_roles'] ?? []);

        $syncPayload = [];
        foreach ($selectedGyms as $gymId) {
            $pivotRole = $gymRoles->get($gymId) ?: $user->role; // fallback to global role
            $syncPayload[$gymId] = ['role' => $pivotRole];
        }
        // Apply sync (will attach/detach as needed)
        $user->gyms()->sync($syncPayload);

        // Update default gym if provided and assigned
        if (!empty($validated['default_gym_id']) && $selectedGyms->contains((int) $validated['default_gym_id'])) {
            $user->default_gym_id = (int) $validated['default_gym_id'];
            $user->save();
        } elseif (empty($validated['default_gym_id'])) {
            // If admin cleared default, set null
            $user->default_gym_id = null;
            $user->save();
        } else {
            // If default gym not among selected, adjust to first selected or null
            $user->default_gym_id = $selectedGyms->first() ?: null;
            $user->save();
        }

        if ($passwordChanged) {
            $user->notify(new PasswordChangedNotification());
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        if ($user->role === 'admin') {
            $otherAdmins = User::where('id', '!=', $user->id)->where('role', 'admin')->count();
            if ($otherAdmins === 0) {
                return back()->withErrors(['error' => 'You cannot delete the last admin.']);
            }
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Reset the user's password to a random temporary password.
     */
    public function resetPassword(User $user)
    {
    $temp = Str::random(12);
    $user->update(['password' => Hash::make($temp)]);
    $user->notify(new PasswordResetByAdminNotification($temp));
    return back()->with('success', 'Password reset and notification sent. Temporary password: ' . $temp);
    }
}
