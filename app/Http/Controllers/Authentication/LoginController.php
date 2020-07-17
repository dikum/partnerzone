<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Title\TitleController;
use App\Http\Controllers\User\UserController;
use Carbon\Carbon;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Exception;

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
    	try{
    		$client_token = $tokenController->getClientCredential();
    	}
    	catch(RequestException $e){
    		Log::error(($e->getResponse()->getBody(true)->getContents()));
    			return response()->json(['message' => 'There was a problem during authentication'], 401);

    	}

    	$email = $request->email;
    	$password = $request->password;

		$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $client_token]]);

		try{
			$response = $client->request('POST', 'http://partnersoft.test/user-login', [
			    'form_params' => [
			    	'email' => $email, 
			    	'password' => $password,
			    	'password_client_id' => \Config::get('constants.oauth.PASSWORD_CLIENT_ID'),
			    	'password_client_secret' => \Config::get('constants.oauth.PASSWORD_CLIENT_SECRET')
				]
			]);
		}
		catch(ClientException $e){
			return response()->json(['message' => 'Invalid login details'], 401);

		}
        catch(OAuthServerException $e){
            return response()->json(['message' => 'Could not authenticate client application']);
        }


		if($response->getStatusCode() == 200)
		{

			$response_body = json_decode((string)$response->getBody()->getContents(), true);


			session(['token' => $response_body['token'], 'token_created_at' => Carbon::now()->timestamp - 10, 'user' => $response_body['user']]);

			return response()->json(['message' => 'success'], 200);
		}

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Invalid login details'], 401);

		return response()->json(['message' => 'Sorry, there was an error. Please try again.'], 500);

    }

    public function logout(){

        try{
            $tokenController = new TokenController();
            $tokenController->validateToken();
        
            $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

            $response = $client->request('GET', 'http://partnersoft.test/logout');
        }
        catch(Exception $e){
            Log::error($e);
        }

    	session()->flush();
    	return redirect('/user-login');
	}


    public function test()
    {
    	
    	dd(isLoggedInUserAdmin());
    }
}
