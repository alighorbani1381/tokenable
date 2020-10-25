<?php

namespace alighorbani1381\TwoFactorAuth;


class TokenGeneratorProvider
{
    static function generateToken()
    {
        return random_int(10000, 100000 - 1);
    }
}
