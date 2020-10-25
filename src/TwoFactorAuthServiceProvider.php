<?php

namespace alighorbani1381\TwoFactorAuth;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use alighorbani1381\TwoFactorAuth\TokenSender;
use alighorbani1381\TwoFactorAuth\Facades\AuthFacade;
use alighorbani1381\TwoFactorAuth\TokenStoreProvider;
use alighorbani1381\TwoFactorAuth\Http\ResponderFacade;
use alighorbani1381\TwoFactorAuth\Facades\TokenStoreFacade;
use alighorbani1381\TwoFactorAuth\Authenticator\SessionAuth;
use alighorbani1381\TwoFactorAuth\Facades\TokenSenderFacade;
use alighorbani1381\TwoFactorAuth\Facades\UserProviderFacade;
use alighorbani1381\TwoFactorAuth\Facades\TokenGeneratorFacade;
use alighorbani1381\TwoFactorAuth\FakeProvider\FakeTokenSender;
use alighorbani1381\TwoFactorAuth\Http\Responses\DefaultResponder;
use alighorbani1381\TwoFactorAuth\FakeProvider\FakeTokenStoreProvider;

class TwoFactorAuthServiceProvider extends ServiceProvider
{

	public $controllerNamespace = 'alighorbani1381\TwoFactorAuth\Http\Controllers';

	public function register()
	{
		$this->setConfigFile();

		$this->initializeFacades();
	}

	public function boot()
	{
		$this->defineRoutes();
	}

	public function initializeFacades()
	{
		if (App::runningUnitTests()) {
			$tokenSender = FakeTokenSender::class;
			$tokenStore = FakeTokenStoreProvider::class;
		} else {
			$tokenSender = TokenSender::class;
			$tokenStore = TokenStoreProvider::class;
		}
		AuthFacade::shouldProxyTo(SessionAuth::class);
		UserProviderFacade::shouldProxyTo(UserProvider::class);
		TokenGeneratorFacade::shouldProxyTo(TokenGeneratorProvider::class);
		TokenStoreFacade::shouldProxyTo($tokenStore);
		ResponderFacade::shouldProxyTo(DefaultResponder::class);
		TokenSenderFacade::shouldProxyTo($tokenSender);
	}

	public function defineRoutes()
	{
		Route::middleware('web')
			->namespace($this->controllerNamespace)
			->group(__DIR__.'/routes.php');
	}

	public function setConfigFile()
	{
		$configPath = __DIR__ . '\config\two_factor_config.php';

		$this->mergeConfigFrom($configPath, 'two_factor');
	}
}
