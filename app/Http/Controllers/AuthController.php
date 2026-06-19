<?php

namespace App\Http\Controllers;

use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
        ]);

        // Badge ID is auto-generated based on role; users cannot provide it.
        $badge = $this->generateBadgeId($request->role);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'badge_id' => $badge,
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Log::info('[Auth] User registered', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('login')->with('status', 'Registrasi berhasil. Silakan login dengan data Anda.');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
        ]);

        Log::info('[Auth] Authenticate attempt', ['email' => $request->email, 'role' => $request->role ?? null, 'ip' => $request->ip()]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            Log::warning('[Auth] User not found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Akun tidak ditemukan.'])->withInput();
        }

        if ($user->locked_until?->isFuture()) {
            Log::warning('[Auth] Attempt to login to locked account', ['user_id' => $user->id, 'locked_until' => $user->locked_until]);
            return back()->withErrors(['email' => 'Akun terkunci sementara. Coba lagi setelah 10 menit.'])->withInput();
        }

        if (! Hash::check($request->password, $user->password) || $user->role !== $request->role) {
            Log::info('[Auth] Credential/field mismatch', ['user_id' => $user->id]);
            $this->registerFailure($user);

            return back()->withErrors(['email' => 'Email, password, atau role tidak sesuai.'])->withInput();
        }

        $otp = $this->generateOtp();
        $secret = $this->getCurrentOtpKey();
        $user->otp_code = hash_hmac('sha256', $otp, $secret);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->failed_login_attempts = 0;
        $user->save();

        // Prefer Mailtrap API if API token present, otherwise use configured mailer (SMTP/log)
        if (! empty(env('MAILTRAP_API_TOKEN'))) {
            $html = view('emails.otp_api', ['name' => $user->name, 'otp' => $otp, 'expiresAt' => $user->otp_expires_at])->render();

            try {
                app(\App\Services\MailtrapApiMailer::class)->send($user->email, 'Kode OTP SistemLoginMatDis', $html);
                Log::info('[Auth] OTP sent via Mailtrap API', ['user_id' => $user->id, 'otp_expires_at' => $user->otp_expires_at->toDateTimeString()]);
            } catch (\Throwable $e) {
                Log::error('[Auth] Mailtrap API send failed, falling back to Mail::to', ['error' => $e->getMessage()]);
                Mail::to($user->email)->send(new OtpCodeMail($user, $otp));
                Log::info('[Auth] OTP sent via fallback SMTP', ['user_id' => $user->id]);
            }
        } else {
            Mail::to($user->email)->send(new OtpCodeMail($user, $otp));
            Log::info('[Auth] OTP sent', ['user_id' => $user->id, 'otp_expires_at' => $user->otp_expires_at->toDateTimeString()]);
        }

        $request->session()->put('auth_user_id', $user->id);

        return redirect()->route('otp.show')->with('status', 'OTP telah dikirim ke email Anda. Silakan cek dan masukkan kode OTP.');
    }

    public function showOtpForm(Request $request)
    {
        if (! $request->session()->has('auth_user_id')) {
            return redirect()->route('login');
        }

        Log::info('[Auth] Showing OTP form', ['session_user' => $request->session()->get('auth_user_id')]);

        $user = null;
        try {
            $user = \App\Models\User::find($request->session()->get('auth_user_id'));
        } catch (\Throwable $e) {
            // ignore
        }

        $canResend = false;
        $otpExpired = true;

        if ($user) {
            $otpExpired = ! $user->otp_expires_at || $user->otp_expires_at->isPast();
            $canResend = $otpExpired;
        }

        return view('auth.otp', ['canResend' => $canResend, 'otpExpired' => $otpExpired]);
    }

    public function resendOtp(Request $request)
    {
        if (! $request->session()->has('auth_user_id')) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($request->session()->get('auth_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->locked_until?->isFuture()) {
            return back()->withErrors(['email' => 'Akun terkunci sementara. Coba lagi nanti.']);
        }

        // Only allow resend if the current OTP has expired or missing
        if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            return back()->with('status', 'OTP saat ini masih berlaku. Silakan cek email Anda.');
        }

        $otp = $this->generateOtp();
        $secret = $this->getCurrentOtpKey();
        $user->otp_code = hash_hmac('sha256', $otp, $secret);
        $user->otp_expires_at = \Illuminate\Support\Carbon::now()->addMinutes(10);
        $user->save();

        // send email (reuse existing logic) - try API then fallback
        if (! empty(env('MAILTRAP_API_TOKEN'))) {
            $html = view('emails.otp_api', ['name' => $user->name, 'otp' => $otp, 'expiresAt' => $user->otp_expires_at])->render();

            try {
                app(\App\Services\MailtrapApiMailer::class)->send($user->email, 'Kode OTP SistemLoginMatDis', $html);
            } catch (\Throwable $e) {
                Mail::to($user->email)->send(new \App\Mail\OtpCodeMail($user, $otp));
            }
        } else {
            Mail::to($user->email)->send(new \App\Mail\OtpCodeMail($user, $otp));
        }

        return back()->with('status', 'OTP baru telah dikirim ke email Anda.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        if (! $request->session()->has('auth_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('auth_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->locked_until?->isFuture()) {
            Log::warning('[Auth] OTP verify on locked account', ['user_id' => $user->id]);
            return redirect()->route('login')->withErrors(['otp_code' => 'Akun terkunci sementara. Coba lagi setelah 10 menit.']);
        }

        $provided = $request->otp_code;
        $keys = $this->getOtpKeys();

        $match = false;
        foreach ($keys as $k) {
            $h = hash_hmac('sha256', $provided, $k);
            if (hash_equals($h, $user->otp_code ?? '')) {
                $match = true;
                break;
            }
        }

        if (! $user->otp_code || ! $match || $user->otp_expires_at->isPast()) {
            Log::info('[Auth] OTP failed verification', ['user_id' => $user->id, 'provided' => $request->otp_code]);
            $this->registerFailure($user);

            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        Auth::login($user);

        Log::info('[Auth] User logged in', ['user_id' => $user->id]);

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();

        $request->session()->forget('auth_user_id');

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function getOtpKeys(): array
    {
        $keys = config('otp.keys', []);

        // Fallback: if config empty, use APP_KEY to avoid breaking older installs
        if (empty($keys)) {
            $keys = [config('app.key')];
        }

        return $keys;
    }

    protected function getCurrentOtpKey(): string
    {
        $keys = $this->getOtpKeys();
        $idx = config('otp.current_index', 0);

        return $keys[$idx] ?? $keys[0];
    }

    protected function validBadgeForRole(string $role, string $badgeId): bool
    {
        $patterns = [
            'Admin' => '/^ADM-\d{3}$/',
            'Manager' => '/^MGR-\d{3}$/',
            'Staff' => '/^STF-\d{3}$/',
            'User' => '/^USR-\d{3}$/',
        ];

        return isset($patterns[$role]) && preg_match($patterns[$role], $badgeId) === 1;
    }

    protected function generateBadgeId(string $role): string
    {
        $prefixMap = [
            'Admin' => 'ADM',
            'Manager' => 'MGR',
            'Staff' => 'STF',
            'User' => 'USR',
        ];

        $prefix = $prefixMap[$role] ?? strtoupper(substr($role, 0, 3));

        $existing = User::where('role', $role)
            ->where('badge_id', 'like', $prefix . '-%')
            ->pluck('badge_id')
            ->toArray();

        $max = 0;
        foreach ($existing as $b) {
            $parts = explode('-', $b);
            $num = intval(end($parts));
            if ($num > $max) {
                $max = $num;
            }
        }

        $next = $max + 1;

        return sprintf('%s-%03d', $prefix, $next);
    }

    public function dashboard()
    {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $role = $user->role;

        switch ($role) {
            case 'Admin':
                return view('dashboard_admin', ['user' => $user]);
            case 'Manager':
                return view('dashboard_manager', ['user' => $user]);
            case 'Staff':
                return view('dashboard_staff', ['user' => $user]);
            case 'User':
            default:
                return view('dashboard_user', ['user' => $user]);
        }
    }

    protected function registerFailure(User $user): void
    {
        $user->failed_login_attempts = ($user->failed_login_attempts ?? 0) + 1;

        if ($user->failed_login_attempts >= 3) {
            $user->locked_until = Carbon::now()->addMinutes(10);
            $user->failed_login_attempts = 0;
        }

        $user->save();
    }
}
