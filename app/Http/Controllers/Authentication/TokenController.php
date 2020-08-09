<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{

    public function getClientCredential(){

    	$client = new \GuzzleHttp\Client();
		$response = $client->request('POST', config('constants.api') .'/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => config('constants.oauth.CLIENT_ID'),
		        'client_secret' => config('constants.oauth.CLIENT_SECRET'),
		        'scope' => '*'
		   	]
		]);

		return json_decode((string) $response->getBody(), true)['access_token'];
    }

    public function checkTokenValidity(){
    	if((Carbon::now()->timestamp - session('token_created_at')) >= 3600)
    		return false;
    	return true;
    }

    public function validateToken(){
    	if((Carbon::now()->timestamp - session('token_created_at')) >= 3600)
    		$this->refreshToken();
    }

    public function getUserToken(){
    	return session('token')['access_token'];
    }

    public function getRefreshToken(){
    	return session('token')['refresh_token'];
    }


    public function refreshToken(){

    	$http = new \GuzzleHttp\Client;

		$response = $http->post(config('constants.api') .'/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'refresh_token',
		        'refresh_token' => $this->getRefreshToken(),
		        'client_id' => config('constants.oauth.PASSWORD_CLIENT_ID'),
		        'client_secret' => config('constants.oauth.PASSWORD_CLIENT_SECRET'),
		        'scope' => '*'
		    ],
		]);

		$this->saveTokenInSession($response->getBody()->getContents());
	}

	public function saveTokenInSession($token){

		$token = json_decode((string)$token, true);
		session(['token_created_at' => Carbon::now()->timestamp - 10]);
		session(['token' => $token]);
	}

}
