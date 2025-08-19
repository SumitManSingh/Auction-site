<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View; // Import the View class
use Illuminate\Http\RedirectResponse; // Import the RedirectResponse class

class UserProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function show(): View
    {
        return view('profile.show');
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                // Check if the new username is unique, but ignore the current user
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Check if the new email is unique, but ignore the current user
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
        ]);

        $user->update($validatedData);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'string'],
        'password' => ['required', 'string', Password::defaults(), 'confirmed'],
    ]);

    $user = $request->user();

    // Check current password
    if (!Hash::check($request->current_password, $user->password_hash)) {
        throw ValidationException::withMessages([
            'current_password' => ['The provided password does not match your current password.'],
        ]);
    }

    // Update to the new password
    $user->update([
        'password_hash' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Password updated successfully!');
}

    public function destroy(Request $request)
    {
        // 1. Validate the password field. It's required for account deletion.
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // 2. Get the currently authenticated user.
        $user = Auth::user();

        // 3. Log out the user before deleting the account.
        // This is a critical step to prevent the user from being logged in
        // after their account has been removed from the database.
        Auth::logout();

        // 4. Delete the user from the database.
        $user->delete();

        // 5. Invalidate the session and regenerate the token for security.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 6. Redirect to the homepage with a success message.
        return redirect()->route('login')->with('status', 'Your account has been deleted successfully.');
    }
}