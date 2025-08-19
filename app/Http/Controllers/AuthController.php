<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Add username and role validation
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(['bidder', 'seller'])],
        ]);

        $user = User::create([
            'username' => $request->username, // Added username field
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role, // Added role field
        ]);

        // // Optional: Send a 2FA code upon registration
        // // Make sure to uncomment these lines if you enable 2FA
        // $otp = rand(100000, 999999);
        // $user->email_otp = $otp;
        // $user->email_otp_expires_at = now()->addMinutes(5);
        // $user->save();
        // Mail::to($user->email)->send(new TwoFactorCodeMail($otp));

        Auth::login($user);

        // Redirect to a more appropriate home or dashboard route after registration
        return redirect()->route('items.index')->with('success', 'Registration successful!');
    }


    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt and two-factor authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validate the email and password fields
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $credentials['email'])->first();

        // Verify the user exists and the password is correct
        if ($user && Hash::check($credentials['password'], $user->password_hash)) {

            // // Generate a 6-digit OTP for 2FA
            // // Make sure to uncomment these lines if you enable 2FA
            // $otp = rand(100000, 999999);
            // $user->email_otp = $otp;
            // $user->email_otp_expires_at = now()->addMinutes(5); // OTP is valid for 5 minutes
            // $user->save();

            // // Send the OTP to the user's email
            // Mail::to($user->email)->send(new TwoFactorCodeMail($otp));

            // // Store the user ID in the session for verification
            // session(['2fa_user_id' => $user->user_id]);

            // // Redirect to the 2FA verification page
            // return redirect()->route('auth.2fa-verify')->with('status', 'A 2FA code has been sent to your email.');

            // Log the user in directly if 2FA is commented out
            Auth::login($user);
            return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
        }

        // If credentials are invalid, throw a validation exception
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Display the 2FA verification form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showVerifyForm()
    {
        // Redirect to login if the 2FA session data is missing
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa-verify');
    }

    /**
     * Handle the 2FA verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        // Validate the OTP
        $request->validate(['otp' => 'required|string|digits:6']);

        // Retrieve the user from the 2FA session
        $user = User::find(session('2fa_user_id'));

        // Check if the user exists and the OTP is valid and not expired
        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'Session expired. Please log in again.']);
        }

        if ($request->otp === (string) $user->email_otp && now()->lt($user->email_otp_expires_at)) {
            // OTP is correct, so log the user in
            Auth::login($user);

            // Clear the OTP data from the user and the session
            $user->email_otp = null;
            $user->email_otp_expires_at = null;
            $user->save();
            session()->forget('2fa_user_id');

            // Regenerate the session and redirect to the intended page
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Login successful!');
        }

        // If OTP is incorrect or expired, send the user back with an error
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    //
    // PASSWORD RESET METHODS
    //

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a password reset link to the given email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Use the Password facade to send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Redirect back with a status message or an error
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset form for a given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password using the provided token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Use the Password facade to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user's password and remember token
                $user->forceFill([
                    'password_hash' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Fire the PasswordReset event
                event(new PasswordReset($user));
            }
        );

        // Redirect to the login page with a success or error message
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    //
    // ALTERNATIVE/CONVENIENCE AUTH METHODS (for route model binding, etc.)
    //

    /**
     * Display the login form (alternative method name for routes).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt (alternative to login()).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->login($request);
    }

    /**
     * Destroy an authenticated session (alternative to logout()).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        return $this->logout($request);
    }

    //
    // EMAIL VERIFICATION METHODS
    //

    /**
     * Show the email verification notice.
     *
     * @return \Illuminate\View\View
     */
    public function showVerificationNotice()
    {
        return view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill(); // Mark the email as verified

        return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Send a new email verification notification to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendVerificationNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    //
    // PASSWORD CONFIRMATION METHODS
    //

    /**
     * Show the password confirmation form.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmForm()
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $request->validate(['password' => 'required']);

        // Check if the provided password matches the user's current password
        if (!Hash::check($request->password, $request->user()->password_hash)) {
            return back()->withErrors(['password' => 'The provided password does not match your current password.']);
        }

        // Store a timestamp to indicate the password has been confirmed
        session(['auth.password_confirmed_at' => time()]);

        // Redirect to the intended page
        return redirect()->intended();
    }
}
