<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailtrapApiMailer
{
    protected string $endpoint = 'https://send.api.mailtrap.io/api/send';

    /**
     * Send an email using Mailtrap Send API.
     *
     * @param string $to
     * @param string $subject
     * @param string $html
     * @return array
     */
    public function send(string $to, string $subject, string $html): array
    {
        $token = env('MAILTRAP_API_TOKEN');

        if (empty($token)) {
            throw new \RuntimeException('MAILTRAP_API_TOKEN not configured');
        }

        $payload = [
            'from' => [
                'email' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
            ],
            'to' => [
                ['email' => $to],
            ],
            'subject' => $subject,
            'html' => $html,
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->post($this->endpoint, $payload);

        if (! $response->successful()) {
            Log::error('[MailtrapApiMailer] send failed', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('Mailtrap API send failed: ' . $response->body());
        }

        return $response->json();
    }
}
