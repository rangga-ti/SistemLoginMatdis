<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $otp;

    public function __construct(User $user, string $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Kode OTP Login Anda')
                    ->markdown('emails.otp')
                    ->with([
                        'name' => $this->user->name,
                        'otpCode' => $this->otp,
                        'expiresAt' => $this->user->otp_expires_at,
                    ]);
    }
}
