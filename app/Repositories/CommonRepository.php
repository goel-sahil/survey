<?php

namespace App\Repositories;

class CommonRepository
{
    /**
     * Generates the random number
     * used in the otps
     */
    public static function genrateRandomNumber(): int
    {
        return rand(111111, 999999);
    }
}
