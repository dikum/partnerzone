<?php

namespace App\Http\Controllers\BankStatement;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Bank\BankController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Currency\CurrencyController;
use App\Http\Controllers\User\UserController;
use App\Traits\BankStatementTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;
//use Maatwebsite\Excel\Facades\Excel;

class BankStatementController extends Controller
{
	use BankStatementTrait;

    public function import_bank_statement(Request $request){
    	$statement_array = null;
    	$insert_data = array();
    	$currencies = null;
    	$this->validate($request, [
      		'bank_statement'  => 'required|mimes:xls,xlsx'
     	]);

     	$filePath = $request->file('bank_statement')->getRealPath();

     	if ( $xlsx = \SimpleXLSX::parse($filePath) ){
     		$statement_array = $xlsx->rows(); 
     		//Log::debug($statement_array);
     	}
		else
			return response()->json(['message' => 'Error parsing Excel file'], 400);


  		if(empty($statement_array))
  			return response()->json(['message' => 'Statement empty']);

   		if(!UserController::isUserLoggedIn())
		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	try{
	    	$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];
		}
		catch(Exception $exception){
			Log::error('Currency Error ' . $exception->getMessage());
			return response()->json(['message' => 'Could not get currency ID']);
		}

  		foreach($statement_array as $statement){
		
  			$insert_data [] = array(

  				'bank_id' => $request->bank_to_import,
  				'transaction_id' => $statement[0],
  				'currency_id' => getCurrencyIDFromCollection($currencies, $statement[1]),
  				'depositor' => $statement[2],
  				'description' => $statement[3],
  				'amount' => $statement[4],
  				'payment_date' => $statement[5] . ' ' . $statement[6],
  				'value_date' => $statement[7] . ' ' . $statement[8]
  			);
  			
  		}
  		unset($insert_data[0]);

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('POST', config('constants.api') .'/bankstatements/store-bulk', [

				'form_params' => $insert_data
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
		return response()->json(['message' => 'Could not import bank statement']);
		    	
	}

    

    public function show(){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->get(config('constants.api') .'/bankstatements/');
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

		if($response->getStatusCode() == 200)

			$currencyController = new CurrencyController();
			$currencies = json_decode($currencyController->index()->getData(), true)['data'];

			$bankController = new BankController();
			$banks = json_decode($bankController->index()->getData(), true)['data'];

			$statements =  json_decode($response->getBody()->getContents(), true)['data'];
			return view('statement.statement', ['statements' => $statements, 'currencies' => $currencies, 'banks' => $banks]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve message information']);
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
			$response = $client->request('GET', config('constants.api') .'/bankstatements' . $search_query);
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

		if($response->getStatusCode() == 200){

			$statements =  json_decode($response->getBody()->getContents(), true)['data'];

			if(count($statements) > 0)
				return view('statement.search_statement_result', ['statements' => $statements]);
			else
				return response('No record found');
		}

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve payment information']);
	}

	public function store(Request $request){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$bankStatement =  json_decode($this->insertBankStatement($request));
    	return $bankStatement;
    	

	}
}
