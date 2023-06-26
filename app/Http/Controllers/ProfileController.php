<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function index() {
        // Check if the authenticated user is an admin
        $admin = auth()->user();
        if (!$admin->admin) {
            return redirect()->back()->with('error', 'You are not authorized to view this page.');
        }

        // Get all users
        $users = User::all();

        return view('profile.index', compact('users'));
    }


    public function updateRole(Request $request, User $user) {
        // Check if the authenticated user is an admin
        $admin = auth()->user();
        if (!$admin->admin) {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        // Validate the incoming request
        $request->validate([
            'admin' => 'required|boolean',
        ]);

        // Update the user's role
        $user->admin = $request->input('admin');
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }


}
