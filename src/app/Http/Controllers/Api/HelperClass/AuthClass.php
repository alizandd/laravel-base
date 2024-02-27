<?php

namespace App\Http\Controllers\Api\HelperClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
class AuthClass
{
    public function token( $user, $password)
    {

        $user->tokens()->where('client_id', env('CLIENT_ID'))->delete();
        $data = [
            'grant_type' => 'password',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'username' => $user->mobile,
            'password' => $password,
        ];
        request()->request->add($data);
        $proxy = Request::create('oauth/token', 'POST');
        return Route::dispatch($proxy);

        //		$request = Request::create('/oauth/token', 'POST', $data);
        //		return app()->handle($request);

    }

    public function refresh( $request ,$scope )
    {
        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'refresh_token' => $request ,
            'scope' => $scope,
        ];
        request()->request->add($data);
        $proxy = Request::create('oauth/token', 'POST');
        return Route::dispatch($proxy);


    }

    public function getToken( $user, $type = 'tv' )
    {
        $user->tokens()->where('client_id', $this->getClientId($type))->delete();
        Passport::personalAccessClientId($this->getClientId($type));
        $token = $user->createToken($type)->accessToken;

        return $token;
    }

    public function deleteToken( $user, $type )
    {
        $user->tokens()->where('client_id', $this->getClientId($type))->delete();

    }
}
