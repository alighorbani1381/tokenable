<?php

namespace alighorbani1381\TwoFactorAuth\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use alighorbani\TwoFactorAuth\Facades\AuthFacade;
use alighorbani\TwoFactorAuth\Facades\TokenStoreFacade;
use alighorbani\TwoFactorAuth\Facades\UserProviderFacade;
use alighorbani\TwoFactorAuth\Facades\TokenGeneratorFacade;
use alighorbani\TwoFactorAuth\Facades\TokenSenderFacade;
use alighorbani\TwoFactorAuth\Http\ResponderFacade;

class TwoFactorAuthController extends Controller
{

    public function requestToken()
    {

        $this->userNotLogin();

        $this->validateRequest();

        $email = request('email');

        $user = UserProviderFacade::getUserByEmail($email)->getOrSend(function () {
            return ResponderFacade::notFoundEmail();
        });

        if (UserProviderFacade::isBanned($user->id)) {
            return ResponderFacade::blockedUser();
        }

        $token = TokenGeneratorFacade::generateToken();

        TokenStoreFacade::tokenStore($token, $user->id);

        TokenSenderFacade::send($token, $user);
    }

    public function loginWithToken()
    {
        $token = request('token');

        $userId = TokenStoreFacade::getUserIdByToken($token)->getOrSend(function () {
            ResponderFacade::tokenNotFound()->throwResponse();
        });;

        AuthFacade::loginWithId($userId);

        return ResponderFacade::loginWithToken();
    }

    public function validateRequest()
    {
        $validator = Validator::make(request()->all(), ['email' => 'required|email']);
        if ($validator->fails()) {
            ResponderFacade::emailNotValid()->throwResponse();
        }
    }

    public function userNotLogin()
    {
        if (AuthFacade::check()) {
            ResponderFacade::loginError()->throwResponse();
        }
    }
}
