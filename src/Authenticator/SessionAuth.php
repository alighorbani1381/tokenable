<?php

namespace alighorbani1381\TwoFactorAuth\Authenticator;

use Illuminate\Support\Facades\Auth;

class SessionAuth
{

    public function check()
    {
        return Auth::check();
    }

    public function loginWithId(int $id)
    {
        return Auth::loginUsingId($id);
    }
}
