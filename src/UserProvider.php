<?php

namespace alighorbani1381\TwoFactorAuth;

use App\User;

class UserProvider
{

    static function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return nullable($user);
    }

    static function isBanned($userId): bool
    {
        $user = User::find($userId) ?: new User;

        return $user->is_ban ? true : false;
    }
}
