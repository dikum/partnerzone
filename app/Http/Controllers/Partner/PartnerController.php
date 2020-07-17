<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Currency\CurrencyController;
use App\Http\Controllers\State\StateController;
use App\Http\Controllers\Title\TitleController;
use App\Http\Controllers\User\UserController;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    public function index(){
    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

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
			$tokenController->refreshToken();
			$this->index();
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

		
		return response()->json(['message' => 'Could not retrieve user information']);
    }


    public function search(Request $request){
    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

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

			if(count($partners) > 0)
				return view('partner.search_partner_result', ['partners' => $partners]);
			else
				return response('No record found');

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);
    	
    }


    public function show(Request $request){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$partner = $request->partner;

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', 'http://partnersoft.test/partners/'.$partner);
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

			$titleController = new TitleController();
			$titles = $titleController->index();

			$countryController = new CountryController();
			$countries = json_decode($countryController->index()->getData(), true)['data'];

			$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];

			$stateController = new StateController();
			$states = json_decode($stateController->index()->getData(), true)['data'];

			$partner = json_decode($response->getBody()->getContents(), true)['data'];

			return view('partner.show_partner', ['partner' => $partner, 'titles' => $titles, 'countries' => $countries, 'states' => $states, 'currencies' => $currencies]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);

	}


    public function update(Request $request){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->patch('http://partnersoft.test/partners/' . $request->userIdentifier, [

				'form_params' => [
				    'partnerIdentifier' => $request->partnerIdentifier,
				    'titleIdentifier' => $request->titleIdentifier,
				    'stateIdentifier' => $request->stateIdentifier,
				    'currencyIdentifier' => $request->currencyIdentifier,
				    'fullname' => $request->fullname,
				    'emailAddress' => $request->emailAddress,
				    'secondaryEmailAddress' => $request->secondaryEmailAddress,
				    'phoneNumber' => $request->phoneNumber,
				    'secondaryPhoneNumber' => $request->secondaryPhoneNumber,
				    'gender' => $request->gender,
				    'birthDate' => $request->birthDate,
				    'maritalStatus' => $request->maritalStatus,
				    'job' => $request->job,
				    'donationAmount' => round($request->donationAmount, 2),
				    'countryOfBirth' => $request->countryOfBirth,
				    'countryOfResidence' => $request->countryOfResidence,
				    'residentialAddress' => $request->residentialAddress,
				    'postalAddress' => $request->postalAddress,
				    'postalAddress' => $request->postalAddress,
				    'preferredLanguage' => $request->preferredLanguage,
				    'userBranch' => $request->userBranch
				]
			]);
		}
		catch(BadResponseException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);

			if($error['code'] == 422)
				return response()->json($error);

			return response()->json(['message' => 'The server encountered an error'], 400);
		}
		catch(ClientException $e){
			Log::error($e->getMessage());

			if($e->getMessage() == 422)
				return response()->json(json_decode($response->getBody()->getContents(), true)['error'], 422);

			return response()->json(['message' => 'There was an error. Please try again.'], 400);
		}
		catch(RequestException $e){
			Log::error($e->getMessage());
			return response()->json(['message' => 'Error. Please check your connection']);
		}
		catch(Exception $e){
			Log::error($e->getMessage());
			return response()->json(['message' => 'There was an error. Please try again.']);
		}

		if($response->getStatusCode() == 200){
			//Log::error($response->getBody()->getContents());
			return response()->json(['message' => 'success'], 200);

		}

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);

	}

	public function delete($partner){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->delete('http://partnersoft.test/partners/' . $partner);
		}
		catch(BadResponseException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			if($error['code'] == 422)
				return response()->json($error);

			return response()->json(['message' => 'The server encountered an error'], 400);
		}
		catch(ClientException $e){
			Log::error($e->getMessage());

			if($e->getMessage() == 422)
				return response()->json(json_decode($response->getBody()->getContents(), true)['error'], 422);

			return response()->json(['message' => 'There was an error. Please try again.'], 400);
		}
		catch(RequestException $e){
			Log::error($e->getMessage());
			return response()->json(['message' => 'Error. Please check your connection']);
		}
		catch(Exception $e){
			Log::error($e->getMessage());
			return response()->json(['message' => 'There was an error. Please try again.']);
		}

		if($response->getStatusCode() == 200){
			//Log::error($response->getBody()->getContents());
			return response()->json(['message' => 'success'], 200);

		}

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);

	}


	public function payments($partner){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', 'http://partnersoft.test/partners/'.$partner.'/payments');
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

			$titleController = new TitleController();
			$titles = $titleController->index();

			$countryController = new CountryController();
			$countries = json_decode($countryController->index()->getData(), true)['data'];

			$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];

			$stateController = new StateController();
			$states = json_decode($stateController->index()->getData(), true)['data'];

			$payments = json_decode($response->getBody()->getContents(), true)['data'];

			return view('partner.partner_payments', ['payments' => $payments]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);

	}
}
