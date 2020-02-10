<?php

namespace App\Http\Controllers\Api\Access;

use App\Http\Controllers\ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function login(Request $request){
        $credentials = $request->only(
            'email',
            'password'
        );

        if(!$token = JWTAuth::attempt($credentials)){
            throw new AuthenticationException('Credenciales de acceso incorrectas');
        }

        $user = JWTAuth::toUser($token);

        return $this->successResponse(['token' => $token, 'user' => $user], 'Las credenciales de acceso son correctas.');
    }

    public function verifyUser(){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        return $this->successResponse($user);
    }

    public function logout(){
        $token = JWTAuth::getToken();

        JWTAuth::setToken($token)->invalidate();

        return $this->showMessage('La sessión del usuario se ha cerrado correctamente');
    }
}