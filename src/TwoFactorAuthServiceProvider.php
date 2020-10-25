<?php

namespace alighorbani1381\TwoFactorAuth;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use alighorbani\TwoFactorAuth\TokenSender;
use alighorbani\TwoFactorAuth\Facades\AuthFacade;
use alighorbani\TwoFactorAuth\TokenStoreProvider;
use alighorbani\TwoFactorAuth\Http\ResponderFacade;
use alighorbani\TwoFactorAuth\Facades\TokenStoreFacade;
use alighorbani\TwoFactorAuth\Authenticator\SessionAuth;
use alighorbani\TwoFactorAuth\Facades\TokenSenderFacade;
use alighorbani\TwoFactorAuth\Facades\UserProviderFacade;
use alighorbani\TwoFactorAuth\Facades\TokenGeneratorFacade;
use alighorbani\TwoFactorAuth\FakeProvider\FakeTokenSender;
use alighorbani\TwoFactorAuth\Http\Responses\ReactResponder;
use alighorbani\TwoFactorAuth\FakeProvider\FakeTokenStoreProvider;

class TwoFactorAuthServiceProvider extends ServiceProvider
{

	public $controllerNamespace = 'TwoFactorAuth\Http\Controllers';

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
		ResponderFacade::shouldProxyTo(ReactResponder::class);
		TokenSenderFacade::shouldProxyTo($tokenSender);
	}

	public function defineRoutes()
	{
		Route::middleware('web')
			->namespace($this->controllerNamespace)
			->group(base_path('two_factor_auth\routes.php'));
	}

	public function setConfigFile()
	{
		$configPath = __DIR__ . '\config\two_factor_config.php';

		$this->mergeConfigFrom($configPath, 'two_factor');
	}
}
