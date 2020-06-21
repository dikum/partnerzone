<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
    	return view('authentication.login');
    }

    public function login_action(Request $request){

    	$tokenController = new TokenController();
    	$token = $tokenController->getClientCredential();

    	$email = $request->email;
    	$password = $request->password;

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $token]]);

    	try{
			$response = $client->request('POST', 'http://partnersoft.test/user-login', [
			    'form_params' => [
			    	'email' => $email, 
			    	'password' => $password,
				]
			]);
		}
		catch(BadResponseException $e){
			dd($e->getResponse()->getBody()->getContents());
		}

		dd($response);
    }
}
