<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function index(){
    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', 'http://partnersoft.test/countries/');
		}
		catch(BadResponseException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'The server encountered an error'], 400);
		}
		catch(ClientException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'There was an error. Please try again.'], 400);
		}
		catch(RequestException $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'Error. Please check your connection']);
		}
		catch(Exception $e){
			Log::error($e->getResponse()->getBody(true)->getContents());
			return response()->json(['message' => 'There was an error. Please try again.']);
		}

		if($response->getStatusCode() == 200)
			return response()->json($response->getBody()->getContents());


		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);
    }
}
