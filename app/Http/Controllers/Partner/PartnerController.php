<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Bank\BankController;
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
			$response = $client->request('GET', config('constants.api') .'/partners/');
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
			$titleController = new TitleController();
			$titles = $titleController->index();

			return view('partner.partners', ['partners' => $partners, 'titles' => $titles]);

		
		return response()->json(['message' => 'Could not retrieve user information']);
    }


    public function search(Request $request){
    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');
    	$search_query = '?';

    	$count = 0;

    	$from_page = null;

    	$keys = $request->keys();

    	foreach($keys as $key){
    		$count++;
    		if($count == 1)
    			continue; //Skip the crsf token which is the first parameter
    		if($key == 'from_send_message'){
    			$from_page = $request->$key;
    			continue;
    		}

    		if($count % 2 != 0)
    			$search_query = $search_query .  $request->$key . '&';

    		else
    			$search_query = $search_query . $request->$key . '=';

    	}

    	$search_query .= 'do-not-paginate';

    	Log::debug($search_query);
    	//$search_query = substr($search_query, 0 , strlen($search_query) -1);

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/partners' . $search_query);
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
				if(isset($from_page)){

					$countryController = new CountryController();
					$countries = json_decode($countryController->index()->getData(), true)['data'];

					return view('message.message_search_partner_result', ['partners' => $partners, 'countries' => $countries]);
				}
				else
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
			$response = $client->request('GET', config('constants.api') .'/partners/'.$partner);
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
			$response = $client->patch(config('constants.api') .'/partners/' . $request->userIdentifier, [

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
		
		return response()->json(['message' => 'Could not retrieve partnership information']);

	}


	public function store(Request $request){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('POST', config('constants.api') .'/partners', [

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
				    'userBranch' => $request->userBranch,
				    'password' => 'nopassword',
				    'password_confirmation' => 'nopassword'
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

		if($response->getStatusCode() == 201){
			//Log::error($response->getBody()->getContents());
			return response()->json(['message' => 'success'], 201);

		}

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		Log::error($response->getBody()->getContents());
		return response()->json(['message' => 'Could not save partners information']);

	}

	public function delete($partner){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->delete(config('constants.api') .'/partners/' . $partner);
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
			$response = $client->request('GET', config('constants.api') .'/partners/'.$partner.'/payments?do-not-paginate');
		}
		catch(BadResponseException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			return response()->json($error);
		}
		catch(ClientException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			return response()->json($error);
		}
		catch(RequestException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			return response()->json($error);
		}
		catch(Exception $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			return response()->json($error);
		}

		if($response->getStatusCode() == 200)

			$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];

			$bankController = new BankController();
			$banks = json_decode($bankController->index()->getData(), true)['data'];

			$payments = json_decode($response->getBody()->getContents(), true)['data'];

			$partner = $this->getPartner($partner);
	
			return view('partner.partner_payments', ['payments' => $payments, 'currencies' => $currencies, 'banks' => $banks, 'partner' => $partner]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);

	}

	public function create(){
		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$titleController = new TitleController();
		$titles = $titleController->index();

		$countryController = new CountryController();
		$countries = json_decode($countryController->index()->getData(), true)['data'];

		$currencyController = new CurrencyController();
		$currencies = json_decode($currencyController->index()->getData(), true)['data'];

		$stateController = new StateController();
		$states = json_decode($stateController->index()->getData(), true)['data'];

		return view('partner.register_partner', ['titles'=>$titles, 'countries'=>$countries, 'currencies'=>$currencies, 'states'=>$states]);
	}

	public function getPartner($partner){

    	$response = null;
    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$this->response = $client->request('GET', config('constants.api') .'/partners/'.$partner);
    	}
    	catch(ClientException $e){
    		Log::error('Could not load partner information: ' . $partner);
    		return response(['message' => 'Could not retrieve user information'], 400);
    	}
	

		if($this->response->getStatusCode() == 200)
			return json_decode($this->response->getBody()->getContents(), true)['data'];
		
		return response(['message' => 'Could not retrieve user information']);
    }
}
