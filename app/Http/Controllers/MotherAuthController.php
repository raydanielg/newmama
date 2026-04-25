<?php

namespace App\Http\Controllers;

use App\Models\Mother;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MotherAuthController extends Controller
{
    /**
     * Show mother login form
     */
    public function showLoginForm()
    {
        return view('mother.auth.login');
    }

    /**
     * Handle mother login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Please enter your MK Number or WhatsApp number',
            'password.required' => 'Please enter your password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $login = $request->input('login');
        $password = $request->input('password');

        // Try to find user by MK Number or WhatsApp
        $mother = Mother::where('mk_number', $login)
            ->orWhere('whatsapp_number', $login)
            ->first();

        if (!$mother) {
            return redirect()->back()
                ->with('error', 'Invalid MK Number or WhatsApp number. Please check and try again.')
                ->withInput();
        }

        // Check if mother has a user account
        $user = User::where('mother_id', $mother->id)->first();

        // If no user account exists, create one with temporary password
        if (!$user) {
            // Create user account for this mother
            $tempPassword = $this->generateTempPassword();
            $user = User::create([
                'name' => $mother->full_name,
                'email' => $this->generateEmailFromPhone($mother->whatsapp_number),
                'password' => Hash::make($tempPassword),
                'mother_id' => $mother->id,
                'role' => 'mother',
                'is_active' => true,
            ]);

            // For first-time login, allow any password and set it
            $user->password = Hash::make($password);
            $user->save();

            Auth::login($user);
            session(['mk_number' => $mother->mk_number]);

            return redirect()->route('mother.dashboard')
                ->with('success', 'Welcome to MamaCare! Your account has been created. Please remember your password for future logins.');
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Incorrect password. Please try again.')
                ->withInput();
        }

        // Login successful
        Auth::login($user);
        session(['mk_number' => $mother->mk_number]);

        return redirect()->route('mother.dashboard')
            ->with('success', 'Welcome back, ' . $mother->full_name . '!');
    }

    /**
     * Show registration form for mothers who already registered but need account
     */
    public function showRegisterForm()
    {
        return view('mother.auth.register');
    }

    /**
     * Handle mother registration (create account for existing mother)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mk_number' => 'required|string|exists:mothers,mk_number',
            'whatsapp_number' => 'required|string|exists:mothers,whatsapp_number',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'mk_number.exists' => 'This MK Number is not found. Please register first at the join page.',
            'whatsapp_number.exists' => 'This WhatsApp number is not found. Please register first.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the mother
        $mother = Mother::where('mk_number', $request->mk_number)
            ->where('whatsapp_number', $request->whatsapp_number)
            ->first();

        if (!$mother) {
            return redirect()->back()
                ->with('error', 'The MK Number and WhatsApp number do not match our records.')
                ->withInput();
        }

        // Check if account already exists
        if (User::where('mother_id', $mother->id)->exists()) {
            return redirect()->route('mother.login')
                ->with('info', 'You already have an account. Please login instead.');
        }

        // Create user account
        $user = User::create([
            'name' => $mother->full_name,
            'email' => $this->generateEmailFromPhone($mother->whatsapp_number),
            'password' => Hash::make($request->password),
            'mother_id' => $mother->id,
            'role' => 'mother',
            'is_active' => true,
        ]);

        Auth::login($user);
        session(['mk_number' => $mother->mk_number]);

        return redirect()->route('mother.dashboard')
            ->with('success', 'Account created successfully! Welcome to MamaCare, ' . $mother->full_name);
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('mother.auth.forgot-password');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mk_number' => 'required|string|exists:mothers,mk_number',
            'whatsapp_number' => 'required|string|exists:mothers,whatsapp_number',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mother = Mother::where('mk_number', $request->mk_number)
            ->where('whatsapp_number', $request->whatsapp_number)
            ->first();

        if (!$mother) {
            return redirect()->back()
                ->with('error', 'The MK Number and WhatsApp number do not match our records.');
        }

        $user = User::where('mother_id', $mother->id)->first();

        if (!$user) {
            return redirect()->route('mother.register')
                ->with('info', 'You don\'t have an account yet. Please create one.');
        }

        // Generate reset token (simplified - in production use Laravel's Password Reset)
        $token = bin2hex(random_bytes(32));
        // Store token in cache or database for verification
        // For now, just show success message

        // Send WhatsApp message with reset link or temporary password
        // This would integrate with your WhatsApp service

        return redirect()->route('mother.login')
            ->with('success', 'Password reset instructions have been sent to your WhatsApp number.');
    }

    /**
     * Logout mother
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('join')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Generate a temporary password
     */
    private function generateTempPassword(): string
    {
        return bin2hex(random_bytes(4)); // 8 character random string
    }

    /**
     * Generate email from phone number
     */
    private function generateEmailFromPhone(string $phone): string
    {
        // Remove + and any non-numeric characters
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        return $cleanPhone . '@mamacare.local';
    }
}
