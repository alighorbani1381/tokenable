<?php

namespace alighorbani1381\TwoFactorAuth\Http\Responses;

use Illuminate\Http\Response;

class DefaultResponder
{
    public function blockedUser()
    {
        return response()->json(['error' => 'Your Blocked!'], Response::HTTP_BAD_REQUEST);
    }

    public function emailNotValid()
    {
        return response()->json(['error' => 'Your Email is Not Valid!'], Response::HTTP_BAD_REQUEST);
    }

    public function loginError()
    {
        return response()->json(['error' => 'You Now is Login'], Response::HTTP_BAD_REQUEST);
    }
    
    public function notFoundEmail()
    {
        return response()->json(['error' => "I Can't Found Email in DB"], Response::HTTP_BAD_REQUEST);
    }

    public function tokenNotFound()
    {
        return response()->json(['error' => "Token Not Found!"], Response::HTTP_NOT_FOUND);
    }
    
    public function loginWithToken()
    {
        return response()->json(['result' => "You're Login Successfuly!"], Response::HTTP_BAD_REQUEST);
    }
    
}
