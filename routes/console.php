<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('otp:rotate', function () {
    $new = bin2hex(random_bytes(16));

    $envPath = base_path('.env');
    if (! file_exists($envPath)) {
        $this->error('.env file not found');
        return 1;
    }

    $env = file_get_contents($envPath);

    // existing OTP_KEYS
    if (preg_match('/^OTP_KEYS=(.*)$/m', $env, $m)) {
        $existing = trim($m[1]);
        $newKeys = $new.','.($existing ?: '');
        $env = preg_replace('/^OTP_KEYS=(.*)$/m', "OTP_KEYS={$newKeys}", $env);
    } elseif (preg_match('/^OTP_SECRET=(.*)$/m', $env, $m2)) {
        $existing = trim($m2[1]);
        $newKeys = $new.','.($existing ?: '');
        // add OTP_KEYS and remove OTP_SECRET for clarity
        $env .= "\nOTP_KEYS={$newKeys}\nOTP_CURRENT=0\n";
    } else {
        // add keys
        $env .= "\nOTP_KEYS={$new}\nOTP_CURRENT=0\n";
    }

    // ensure OTP_CURRENT is set to 0
    if (! preg_match('/^OTP_CURRENT=/m', $env)) {
        $env .= "OTP_CURRENT=0\n";
    } else {
        $env = preg_replace('/^OTP_CURRENT=.*$/m', 'OTP_CURRENT=0', $env);
    }

    file_put_contents($envPath, $env);

    $this->info('New OTP key generated and added to .env: '.$new);
    $this->info('OTP_CURRENT set to 0 (new key is active).');

    return 0;
})->purpose('Generate a new OTP HMAC key and prepend it to OTP_KEYS in .env');

use App\Services\AuthCombinatorics;

Artisan::command('auth:stats', function () {
    $c = AuthCombinatorics::class;

    $this->info('Roles: '.implode(', ', AuthCombinatorics::roles()));
    $this->info('Badges per role: '.AuthCombinatorics::badgesPerRole());
    $this->info('Total badge combinations: '.AuthCombinatorics::humanNumber(AuthCombinatorics::totalBadgeCombinations()));
    $this->info('OTP space (6-digit): '.AuthCombinatorics::humanNumber(AuthCombinatorics::otpSpace()));
    $this->info('Total badge×OTP combos: '.AuthCombinatorics::humanNumber(AuthCombinatorics::totalValidOtpCombinations()));
    $this->info('Probability single OTP guess: '.AuthCombinatorics::probabilitySingleOtpGuess());

    return 0;
})->purpose('Show combinatorics and probability stats for authentication');
