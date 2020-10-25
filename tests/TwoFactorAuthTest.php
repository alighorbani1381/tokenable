<?php

namespace alighorbani1381\TwoFactorAuth\tests;


use Illuminate\Foundation\Auth\User;
use alighorbani1381\TwoFactorAuth\tests\TestCase;
use alighorbani1381\TwoFactorAuth\Facades\AuthFacade;
use alighorbani1381\TwoFactorAuth\Http\ResponderFacade;
use alighorbani1381\TwoFactorAuth\Facades\UserProviderFacade;
use alighorbani1381\TwoFactorAuth\Facades\TokenGeneratorFacade;



class TwoFactorAuthTest extends TestCase
{

    public function test_the_happy_path()
    {

        User::unguard();

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
        User::unguard();
        
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
