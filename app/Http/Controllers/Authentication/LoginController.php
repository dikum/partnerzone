<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index(){
    	if(Cookie::get('user'))
    		return redirect('/');
    	return view('authentication.login');
    }

    public function login_action(Request $request){

    	$tokenController = new TokenController();
    	$client_token = $tokenController->getClientCredential();

    	$email = $request->email;
    	$password = $request->password;

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $client_token]]);


    	try{
			$response = $client->request('POST', 'http://partnersoft.test/user-login', [
			    'form_params' => [
			    	'email' => $email, 
			    	'password' => $password
				]
			]);
		}
		catch(BadResponseException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'The server encountered an error']);
		}
		catch(ClientException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			if($response->getStatusCode() == 401)
				return response()->json(['message' => 'Invalid login details'], 401);
			return response()->json(['message' => 'There was an error. Please try again.']);
		}
		catch(RequestException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'Error. Please check your connection']);
		}


		if($response->getStatusCode() == 200)
		{

			$response_content = $response->getBody()->getContents();

			$response_content_decoded = json_decode($response_content, true);

			Cookie::queue('password_token', $response_content_decoded['token']);
			Cookie::queue('user', $response_content_decoded['user']['user_id']);

			if(session('intended'))
				return redirect(session('intended'));

			return response()->json(['message' => 'success'], 200);
		}

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Invalid login details'], 401);

		return response()->json(['message' => 'Sorry, there was an error. Please try again.'], 500);
		



    }

    public function test()
    {
    	return Cookie::get('password_token');
    }
}
