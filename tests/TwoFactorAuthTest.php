<?php

namespace alighorbani1381\TwoFactorAuth\tests;

use App\User;
use alighorbani\TwoFactorAuth\Facades\AuthFacade;
use alighorbani\TwoFactorAuth\Http\ResponderFacade;
use alighorbani\TwoFactorAuth\Facades\UserProviderFacade;
use alighorbani\TwoFactorAuth\Facades\TokenGeneratorFacade;
use alighorbani1381\TwoFactorAuth\TestCase as TwoFactorAuthTestCase;


class TwoFactorAuthTest extends TwoFactorAuthTestCase
{

    public function test_the_happy_path()
    {

        $user = new User(['id' => 1, 'email' => 'ali@gmail.com']);

        UserProviderFacade::shouldReceive('getUserByEmail')
            ->andReturn(nullable($user));

        UserProviderFacade::shouldReceive('isBanned')->andReturn(false);

        TokenGeneratorFacade::shouldReceive('generateToken');

        $this->postJson('api/requestToken', ['email' => 'ali@gmail.com']);
    }

    public function test_email_not_found()
    {
        $user = null;

        UserProviderFacade::shouldReceive('getUserByEmail')
            ->andReturn(nullable($user));

        ResponderFacade::shouldReceive('notFoundEmail');

        UserProviderFacade::shouldReceive('isBanned')->never();

        TokenGeneratorFacade::shouldReceive('generateToken')->never();

        $this->postJson('api/requestToken', ['email' => 'ali@gmail.com']);
    }

    public function test_user_is_blocked()
    {
        $user = new User(['id' => 1, 'email' => 'ali@gmail.com']);

        UserProviderFacade::shouldReceive('getUserByEmail')
            ->andReturn(nullable($user));

        UserProviderFacade::shouldReceive('isBanned')->andReturn(true);

        TokenGeneratorFacade::shouldReceive('generateToken')->never();

        $this->postJson('api/requestToken', ['email' => 'ali@gmail.com']);
    }

    public function test_the_email_not_valid()
    {
        UserProviderFacade::shouldReceive('getUserByEmail')->never();

        UserProviderFacade::shouldReceive('isBanned')->never();

        TokenGeneratorFacade::shouldReceive('generateToken')->never();

        $this->postJson('api/requestToken', ['email' => 'ali_gmail.com']);
    }

    public function test_the_user_is_login()
    {
        AuthFacade::shouldReceive('check')->andReturn(true);

        UserProviderFacade::shouldReceive('getUserByEmail')->never();

        ResponderFacade::shouldReceive('loginError');

        $this->postJson('api/requestToken', ['email' => 'ali@gmail.com']);
    }
}
