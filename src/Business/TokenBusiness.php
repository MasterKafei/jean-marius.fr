<?php

namespace App\Business;

class TokenBusiness
{
    public function generateRandomToken($base = 36): string
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, $base);
    }
}
