<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use Carbon\Carbon;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index(){
    	if(UserController::isUserLoggedIn())
    		return redirect('/');
    	return view('authentication.login');
  
    }

    public function login_action(Request $request){

    	$credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

    	$tokenController = new TokenController();
    	$client_token = $tokenController->getClientCredential();

    	$email = $request->email;
    	$password = $request->password;

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $client_token]]);


    	try{
			$response = $client->request('POST', 'http://partnersoft.test/user-login', [
			    'form_params' => [
			    	'email' => $email, 
			    	'password' => $password,
			    	'password_client_id' => PASSWORD_CLIENT_ID,
			    	'password_client_secret' => PASSWORD_CLIENT_SECRET
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

			$response_body = json_decode((string)$response->getBody()->getContents(), true);

			$response_body['token']['created_at'] = Carbon::now()->timestamp;

			session(['token' => $response_body['token'], 'user' => $response_body['user']['user_id']]);

			return response()->json(['message' => 'success'], 200);
		}

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Invalid login details'], 401);

		return response()->json(['message' => 'Sorry, there was an error. Please try again.'], 500);

    }

    public function logout(){

    	session()->flush();
    	return redirect('/user-login');
	}


    public function test()
    {
    	dd(session('token'));
    }
}
