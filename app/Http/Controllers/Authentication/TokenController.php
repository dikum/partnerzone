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
		$response = $client->request('POST', 'http://partnersoft.test/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => CLIENT_ID,
		        'client_secret' => CLIENT_SECRET,
		        'scope' => '*'
		   	]
		]);

		return json_decode((string) $response->getBody(), true)['access_token'];
    }


    public function getUserToken(){
    	return session('token')['access_token'];
    }

    private function getRefreshToken(){
    	return session('token')['refresh_token'];
    }


    public function refreshToken(){
    	$http = new \GuzzleHttp\Client;

		$response = $http->post('http://partnersoft.test/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'refresh_token',
		        'refresh_token' => $this->getRefreshToken(),
		        'client_id' => PASSWORD_CLIENT_ID,
		        'client_secret' => PASSWORD_CLIENT_SECRET,
		        'scope' => '*',
		    ],
		]);



		$this->saveTokenInSession($response->getBody()->getContents());
	}

	public function validateToken(){
		if(Carbon::now()->timestamp > session('token')['created_at'] + session('token')['expires_in'])
            $this->refreshToken();
	}

	public function saveTokenInSession($token){

		$token = json_decode((string)$token, true);
		$token['created_at'] = Carbon::now()->timestamp;
		session(['token' => $token]);
	}

}
