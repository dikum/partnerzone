<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Jobs\SendBulkMessageJob;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    public function index(){

    }

    public function create(){
    	$templates = $this->get_templates();
    	return view('message.create_message', ['templates' => $templates]);
    }

    public function get_templates(){
    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();
    	
    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/messagetemplates/');
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

			return json_decode($response->getBody()->getContents(), true)['data'];
		
		return response()->json(['message' => 'Could not retrieve user information']);
    }

    public function get_template($template){
    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();
    	
    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/messagetemplates/'.$template);
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
			return json_decode($response->getBody()->getContents(), true)['data'];
		
		return response()->json(['message' => 'Could not retrieve user information']);
    }

    public function show_templates(){
    	$templates = $this->get_templates();
    	return view('message.message_templates', ['templates' => $templates]);
    }

    public function create_template(){
    	return view('message.create_message_template');
    }

    public function save_template(Request $request){

    	if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
    		if(isset($request->messageTemplateIdentifier))
				$response = $client->patch(config('constants.api') .'/messagetemplates/' . $request->messageTemplateIdentifier, [

					'form_params' => [
					    'messageTitle' => $request->messageTitle,
					    'messageTemplate' => $request->messageTemplate
					]
				]);


			else
				$response = $client->post(config('constants.api') .'/messagetemplates', [

				'form_params' => [
				    'messageTitle' => $request->messageTitle,
				    'messageTemplate' => $request->messageTemplate
				]
			]);

		}
		catch(BadResponseException $e){
			Log::error($e->getMessage());
			$error = json_decode($e->getResponse()->getBody()->getContents(), true);
			if($error['code'] == 403)
				return response()->json(json_encode($error), 403);

			if($error['code'] == 422)
				return response()->json($error, 422);

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


		if($response->getStatusCode() == 200 || $response->getStatusCode() == 201){
			//Log::error($response->getBody()->getContents());
			return response()->json(['message' => 'success'], 200);

		}

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'There was an error. Please try again.']);
    }

    public function delete($message){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->delete(config('constants.api') .'/messagetemplates/' . $message);
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
		
		return response()->json(['message' => 'Could not retrieve message information']);

	}

	public function send(Request $request){
		Log::error($request);
		$messageValidated = $this->validateMessage($request);

		if(gettype($messageValidated) == 'string')
			return response()->json(['message' => $messageValidated]);

		dispatch(new SendBulkMessageJob($request->all()));

		return response()->json(['message' => 'success']);

		/*$send_list = $request->send_list;
		$table_data = $request->tableData;
		$message = $request->messageEditor;



		if(isset($send_list)){

			$this->send_to_list($send_list, $message);
		}

		if(isset($table_data)){

			$this->send_to_table_data($table_data, $message);
		}
		*/

		return response()->json(['message' => 'success']);
	}

	private function validateMessage(Request $request){

		if(!isset($request->send_list) && !isset($request->tableData))
			return 'Provide a list to send message to.';

		if(!isset($request->send_as_email) & !isset($request->send_as_sms))
			return 'Select Email or SMS';

		if(!isset($request->messageEditor))
			return 'Provide message content';

		return TRUE;
	}

	private function send_to_list($send_list, $message){

		foreach($send_list as $message_to){
			$message_to_send = $this->replacePlaceHolders($message);

			if(is_numeric(substr(str_replace(' ', '', $message_to_send), 0,1))) //Remove the '+' and empty spaces
				$this->send_sms($message_to, $message_to_send);
			else
				$this->send_email($message_to, $message_to_send);
			}
	}

	private function send_to_table_data($table_data, $message){

		foreach($table_data as $partner){

			$message_to_send = $this->replacePlaceHolders($partner, $message);

			$phone = $partner['phone'];
			$email = $partner['email'];

			if(isset($request->send_as_email) && isset($email))
				$this->send_email($email, $message_to_send);

			if(isset($request->send_as_sms) && isset($phone))
				$this->send_sms($phone, $message_to_send);

			}
	}

	public function send_email($email, $message){

	}

	public function send_sms($phone, $message){

		$message = strip_tags($message);
	}

	public function replacePlaceHolders($table_row, $message){

		$message = str_replace('[PARTNERID]', $table_row['ID'], $message);
		$message = str_replace('[NAME]', $table_row['Name'], $message);
		$message = str_replace('[EMAIL]', $table_row['Email'], $message);
		$message = str_replace('[PHONE]', $table_row['Phone'], $message);
		$message = str_replace('[GENDER]', $table_row['Gender'], $message);
		$message = str_replace('[DATEOFBIRTH]', $table_row['Date Of Birth'], $message);
		$message = str_replace('[MARITALSTATUS]', $table_row['Marital Status'], $message);
		$message = str_replace('[OCCUPATION]', $table_row['Occupation'], $message);
		$message = str_replace('[BIRTHCOUNTRY]', $table_row['Birth Country'], $message);
		$message = str_replace('[RESIDENTIALCOUNTRY]', $table_row['Resident Country'], $message);
		$message = str_replace('[RESIDENTIALADDRESS]', $table_row['Residential Address'], $message);
		$message = str_replace('[POSTALADDRESS]', $table_row['Postal Address'], $message);
	}
}
