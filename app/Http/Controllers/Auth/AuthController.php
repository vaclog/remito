<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //

    public function login(Request $request){
        $guzzle = new GuzzleHttp\Client;

        $response = $guzzle->post(config('services.passport.login_endpoint'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->username,
                'password' => $request->password
            ],
        ]);

        return json_decode((string) $response->getBody(), true)['access_token'];
    }
}
