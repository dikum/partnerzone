<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Bank\BankController;
use App\Http\Controllers\Currency\CurrencyController;

class PaymentController extends Controller
{
    

	public function index(){
		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/payments?sort_by=createdDate');
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

			return view('payment.payments', ['payments' => $payments, 'currencies' => $currencies, 'banks' => $banks]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);
	}


	public function delete($payment){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->delete(config('constants.api') .'/payments/' . $payment);
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

	public function search(Request $request){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$search_query = '?';

    	$count = 0;

    	foreach($request->all() as $field => $value){
    		$count++;
    		if($count == 1)
    			continue; //Skip the crsf token which is the first parameter
    		if(isset($value))
    			$search_query = $search_query .  $field . '=' . $value . '&';

    	}

    	$search_query .= 'do-not-paginate';
    	//$search_query = substr($search_query, 0 , strlen($search_query) -1); //Remove the last ampersand

    	Log::debug($search_query);

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/payments' . $search_query);
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

			$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];

			$bankController = new BankController();
			$banks = json_decode($bankController->index()->getData(), true)['data'];

			$payments = json_decode($response->getBody()->getContents(), true)['data'];

			if(count($payments) > 0)
				return view('payment.search_payment_result', ['payments' => $payments, 'currencies' => $currencies, 'banks' => $banks]);
			else
				return response('No record found');

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve payment information']);
	}
}
