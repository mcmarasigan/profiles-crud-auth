<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Handles all CRUD operations for user profiles on the dashboard.
 * Access is controlled by auth middleware and admin middleware.
 */
class ProfileController extends Controller
{
    /**
     * Display the dashboard with a list of user profiles.
     * Admin sees all users; regular users see only their own profile.
     */
    public function index(Request $request)
    {
        $query = User::withTrashed(false); // Exclude soft-deleted users

        // Role-based: admins see all, users see only themselves
        if (!Auth::user()->isAdmin()) {
            $query->where('id', Auth::id());
        }

        // Search functionality
        if ($search = $request->get('search')) {
            $sanitized = strip_tags($search);
            $query->where(function ($q) use ($sanitized) {
                $q->where('name', 'like', '%' . $sanitized . '%')
                  ->orWhere('email', 'like', '%' . $sanitized . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.index', compact('users'));
    }

    /**
     * Show the form to create a new profile.
     * Admin only.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a new user profile in the database.
     * Admin only. Validates via ProfileRequest.
     */
    public function store(ProfileRequest $request)
    {
        $data = $request->validated();

        // Hash password securely
        $data['password'] = Hash::make($data['password']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploadAvatar($request);
        }

        // Sanitize text fields against XSS
        $data['name']    = strip_tags($data['name']);
        $data['bio']     = isset($data['bio']) ? strip_tags($data['bio']) : null;
        $data['address'] = isset($data['address']) ? strip_tags($data['address']) : null;

        User::create($data);

        return redirect()->route('dashboard')
            ->with('success', 'Profile created successfully!');
    }

    /**
     * Display a single user profile.
     */
    public function show(User $user)
    {
        // Non-admin users can only view their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Access denied.');
        }

        return view('dashboard.show', compact('user'));
    }

    /**
     * Show the edit form for a user profile.
     */
    public function edit(User $user)
    {
        // Non-admin users can only edit their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Access denied.');
        }

        return view('dashboard.edit', compact('user'));
    }

    /**
     * Update a user profile in the database.
     * Validates via ProfileRequest. Password update is optional.
     */
    public function update(ProfileRequest $request, User $user)
    {
        // Non-admin users can only update their own profile
        if (!Auth::user()->isAdmin() && Auth::id() !== $user->id) {
            abort(403, 'Access denied.');
        }

        $data = $request->validated();

        // Remove password from data if not being changed
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                @unlink(public_path('avatars/' . $user->avatar));
            }
            $data['avatar'] = $this->uploadAvatar($request);
        }

        // Non-admin users cannot change their own role
        if (!Auth::user()->isAdmin()) {
            unset($data['role']);
        }

        // Sanitize text fields
        $data['name']    = strip_tags($data['name']);
        $data['bio']     = isset($data['bio']) ? strip_tags($data['bio']) : null;
        $data['address'] = isset($data['address']) ? strip_tags($data['address']) : null;

        $user->update($data);

        return redirect()->route('dashboard')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Soft-delete a user profile.
     * Admin only. Cannot delete own account.
     */
    public function destroy(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can delete profiles.');
        }

        // Prevent admin from deleting their own account
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete(); // Soft delete

        return redirect()->route('dashboard')
            ->with('success', 'Profile "' . e($user->name) . '" has been deleted.');
    }

    /**
     * Handle avatar file upload to public/avatars directory.
     * Returns the stored filename.
     */
    private function uploadAvatar(Request $request): string
    {
        $file      = $request->file('avatar');
        $filename  = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move(public_path('avatars'), $filename);
        return $filename;
    }
}
