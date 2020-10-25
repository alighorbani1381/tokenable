<?php

namespace alighorbani1381\TwoFactorAuth\tests;

use alighorbani1381\TwoFactorAuth\TwoFactorAuthServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TwoFactorAuthServiceProvider::class];
    }
}