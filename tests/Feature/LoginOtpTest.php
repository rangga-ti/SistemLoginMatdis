<?php

namespace Tests\Feature;

use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LoginOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_with_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'badge_id' => 'ADM-123',
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'badge_id' => 'ADM-123',
        ]);

        $response->assertRedirect(route('otp.show'));

        $otpSent = null;

        Mail::assertSent(OtpCodeMail::class, function ($mail) use (&$otpSent, $user) {
            $otpSent = $mail->otp;
            return $mail->hasTo($user->email);
        });

        $this->assertNotNull($otpSent);

        $response2 = $this->post('/otp', ['otp_code' => $otpSent]);

        $response2->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_lockout_after_three_failures(): void
    {
        $user = User::factory()->create([
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'User',
            'badge_id' => 'USR-001',
        ]);

        // three bad password attempts
        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', [
                'email' => 'bob@example.com',
                'password' => 'wrong',
                'role' => 'User',
                'badge_id' => 'USR-001',
            ]);
        }

        $user->refresh();

        $this->assertNotNull($user->locked_until);
        $this->assertTrue($user->locked_until->isFuture());

        // attempt correct credentials while locked
        $resp = $this->post('/login', [
            'email' => 'bob@example.com',
            'password' => 'password',
            'role' => 'User',
            'badge_id' => 'USR-001',
        ]);

        $resp->assertSessionHasErrors();
    }

    public function test_otp_expiry_blocks_login(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'sue@example.com',
            'password' => Hash::make('password'),
            'role' => 'Staff',
            'badge_id' => 'STF-010',
        ]);

        $this->post('/login', [
            'email' => 'sue@example.com',
            'password' => 'password',
            'role' => 'Staff',
            'badge_id' => 'STF-010',
        ]);

        $otpSent = null;

        Mail::assertSent(OtpCodeMail::class, function ($mail) use (&$otpSent) {
            $otpSent = $mail->otp;
            return true;
        });

        $this->assertNotNull($otpSent);

        // expire the OTP
        $user->refresh();
        $user->otp_expires_at = now()->subMinutes(1);
        $user->save();

        $resp = $this->post('/otp', ['otp_code' => $otpSent]);

        $resp->assertSessionHasErrors();
        $this->assertGuest();
    }
}
