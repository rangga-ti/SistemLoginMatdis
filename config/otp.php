<?php

return [
    // OTP keys can be provided as a comma-separated list in OTP_KEYS env var.
    // The first key is considered the current (used to sign new OTPs).
    // Example: OTP_KEYS=keynew,keyold
    'keys' => array_filter(array_map('trim', explode(',', env('OTP_KEYS', env('OTP_SECRET', ''))))),

    // Current active index (0-based) if needed. Default 0 (first key).
    'current_index' => (int) env('OTP_CURRENT', 0),
];
