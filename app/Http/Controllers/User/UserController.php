<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\AuthenticationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

	public function __construct(){
		//$this->middleware('validate.token:')->except(['isUserLoggedIn']);

	}

    public static function isUserLoggedIn(){
    	if(session()->has('token') && session()->has('user'))
    		return true;
    	return false;
    }

    public function getUser($user){

    	$response = null;
    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$this->response = $client->request('GET', 'http://partnersoft.test/users/'.$user);
    	}
    	catch(ClientException $e){
    		Log::error('Could not load user information: ' . session('user'));
    		Log::error($e);
    		return response(['message' => 'Could not retrieve user information'], 400);
    	}
	

		if($this->response->getStatusCode() == 200)
			return json_decode($this->response->getBody()->getContents());
		
		return response(['message' => 'Could not retrieve user information']);
    }
}
