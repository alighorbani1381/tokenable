<?php

use Illuminate\Support\Facades\Route;
use alighorbani1381\TwoFactorAuth\Facades\TokenStoreFacade;

Route::post("requestToken", "TwoFactorAuthController@requestToken")->name('twofactor.login');


Route::get("checkToken", "TwoFactorAuthController@loginWithToken")->name('twofactor.checkToken');



# Manual Tests From Low Level Implementation
if (app()->environment() == "local") {


    Route::group(['prefix' => 'test'], function () {


        // Test Cache token and remove it
        Route::get('token/store', function () {

            config()->set('two_factor.token-destroy-after', 3);
            
            TokenStoreFacade::tokenStore('1a2ad5c', 12);

            sleep(1.1);

            $token = TokenStoreFacade::getUserIdByToken('1a2ad5c');
            
            if ($token != 12) {
                dd("Token don't get");
            }
            
            $token = TokenStoreFacade::getUserIdByToken('1a2ad5c');

            if (!is_null($token)) {
                dd("Token Don't Destroy After Twice Call");
            }

            config()->set('two_factor.token-destroy-after', 1);

            TokenStoreFacade::tokenStore('aaa', 12);

            sleep(1.1);
           
            $token = TokenStoreFacade::getUserIdByToken('aaa');

            if (!is_null($token)) {
                dd("Token Don't Destroy After Twice Call");
            }


            dd("Cache test are passed !");
        });
    });
}
