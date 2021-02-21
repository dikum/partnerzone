<?php
 
namespace App\Traits;

use App\Http\Controllers\Authentication\TokenController;
use Illuminate\Support\Facades\Log;
 
trait BankStatementTrait {
 
    public function insertStrongRoomStatement($request) {

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('POST', config('constants.api') .'/bankstatements/store-sr', [

				'form_params' => [
	  				'currency_id' => $request->currencyIdentifier,
	  				'depositor' => $request->paymentDepositor,
	  				'description' => $request->paymentDescription,
	  				'amount' => $request->amountPaid,
	  				'payment_date' => $request->datePaid,
	  				'value_date' => $request->datePaid
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
			return response()->json(['message' => 'Error. Please check your connection'], 422);
		}
		catch(Exception $e){
			Log::error($e->getMessage());
			return response()->json(['message' => 'There was an error. Please try again.'], 500);
		}

		if($response->getStatusCode() == 201){
			//Log::error($response->getBody()->getContents());
			$bankstatement = $response->getBody()->getContents();
			return response()->json(['message' => 'success', 'bank_statement' => $bankstatement], 201);

		}

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page'], 401);
		
		Log::error($response->getBody()->getContents());
		return response()->json(['message' => 'Could not save bank statement'], 500);
 
    }
 
}