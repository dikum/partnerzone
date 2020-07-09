<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    public function index(){
    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', 'http://partnersoft.test/partners/');
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

			$partners = json_decode($response->getBody()->getContents(), true)['data'];

			return view('partner.partners', ['partners' => $partners]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);
    }


    public function search(Request $request){

    	$search_query = '?';

    	$count = 0;

    	$keys = $request->keys();

    	foreach($keys as $key){
    		$count++;
    		if($count == 1)
    			continue; //Skip the crsf token which is the first parameter

    		if($count % 2 != 0)
    			$search_query = $search_query .  $request->$key . '&';

    		else
    			$search_query = $search_query . $request->$key . '=';

    	}

    	$search_query = substr($search_query, 0 , strlen($search_query) -1);

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', 'http://partnersoft.test/partners' . $search_query);
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

			$partners = json_decode($response->getBody()->getContents(), true)['data'];

		Log::error($partners);

			if(count($partners) > 0)
				return view('partner.search_partner_result', ['partners' => $partners]);
			else
				return response('No record found');

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);



    	
    }
}
