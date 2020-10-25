<?php

namespace alighorbani1381\TwoFactorAuth;


class TokenStoreProvider
{
    const TOKEN_AFFIX = '-2FactorAuth';

    public function tokenStore($token, $userId)
    {
        $destroyTime = config('two_factor.token-destroy-after');

        return cache()->set($token . self::TOKEN_AFFIX, $userId, $destroyTime);
    }

    public function getUserIdByToken($token)
    {
        $userToken = cache()->pull($token . self::TOKEN_AFFIX);
        
        return nullable($userToken);
    }
}
