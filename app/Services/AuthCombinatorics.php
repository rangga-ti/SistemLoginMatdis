<?php

namespace App\Services;

class AuthCombinatorics
{
    /**
     * Roles used in the system.
     * @return array<int,string>
     */
    public static function roles(): array
    {
        return ['Admin', 'Manager', 'Staff', 'User'];
    }

    /**
     * Number of badge combinations per role (format XXX-000..999).
     */
    public static function badgesPerRole(): int
    {
        return 1000;
    }

    /**
     * Total badge combinations across all roles.
     */
    public static function totalBadgeCombinations(): int
    {
        return count(self::roles()) * self::badgesPerRole();
    }

    /**
     * OTP space for 6-digit codes.
     */
    public static function otpSpace(): int
    {
        return 1000000;
    }

    /**
     * Total valid OTP combinations when combined with badges.
     */
    public static function totalValidOtpCombinations(): int
    {
        return self::totalBadgeCombinations() * self::otpSpace();
    }

    /**
     * Probability of guessing OTP in one try.
     */
    public static function probabilitySingleOtpGuess(): float
    {
        return 1 / self::otpSpace();
    }

    /**
     * Probability of success after N independent guesses (approx).
     */
    public static function probabilityAfterNGuesses(int $n): float
    {
        $p = $n / self::otpSpace();
        return $p > 1 ? 1.0 : $p;
    }

    public static function humanNumber(int $n): string
    {
        return number_format($n, 0, '.', ',');
    }
}
