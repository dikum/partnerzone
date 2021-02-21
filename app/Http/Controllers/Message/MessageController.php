<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper\PostCaller;
use App\Http\Controllers\PusherNotification\PusherController;
use App\Http\Controllers\User\UserController;
use App\Jobs\SendBulkEmailJob;
use App\Jobs\SendBulkSmsJob;
use App\Jobs\SendBulkListEmailJob;
use App\Mail\SendMessageMailable;
use App\Notification;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
class MessageController extends Controller
{

   private $message_progress = [

        'total' => 0,
        'success' => 0,
        'fails' => []
    ];

    private $pusher;

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

	public function send_bulk(Request $request){
		$formData;
		$tableData = $request->tableData;
		$send_list = [];
		$send_list_count = 0;
        $tableDataCount = 0;

		$i = 0;

		while($i < count($request->formData)){
			$formData[$request->formData[$i]['name']] = $request->formData[$i]['value'];
			$i++;
		}

		$messageValidated = $this->validateMessage($formData, $tableData);

		if(gettype($messageValidated) == 'string')
			return response()->json(['message' => $messageValidated]);

		if(isset($tableData))
            $tableDataCount = count($tableData);
        else
        	$tableData = [];

        if(isset($formData['send_list'])){
            $send_list = explode(',', $formData['send_list']);
            $send_list_count = count($send_list);
        }

        $message = $formData['messageEditor'];

        $totalMessages = $send_list_count + $tableDataCount;

        $this->message_progress['total'] = $totalMessages;
       
        Log::info('Total Message List Count: ' . $totalMessages);

        if(count($tableData))
        	$this->send_message_to_table_data($tableData, $formData, $message, $this->message_progress);

        if(count($send_list))
        	$this->send_message_to_list($send_list, $formData, $message, $this->message_progress, $messageJobIdentifier);

		return response()->json(['message' => 'success']);

	}

	private function validateMessage($formData, $tableData){

		if(!isset($formData['send_list']) && !isset($tableData))
			return 'Provide a list to send message to.';

		if(!isset($formData['send_as_email']) & !isset($formData['send_as_sms']))
			return 'Select Email or SMS';

		if(!isset($formData['messageEditor']))
			return 'Provide message content';

		return TRUE;
	}

	
	public function send_sms($phone, $message){
		$message = strip_tags($message);

		/*if(substr($phone, 0,4) == '+234' || substr($phone, 0,4) == '+356'){
			
			return $this->send_sms_vas2nets($phone, $message);
		}
		else{
			
			return $this->send_sms_twilio($phone, $message);
		}
		*/
		return $this->send_sms_twilio($phone, $message);
	}

	public function send_sms_twilio($phone, $message){

		try{
			$client = new Client(config('constants.message_settings.twilio_account_sid'), config('constants.message_settings.twilio_account_token'));
			$client->messages->create(

				$phone,
				array(
					'from' => config('constants.message_settings.sms_sender'),
					'body' => $message
				)
			);

			return true;
		}
		catch(Exception $exception){
			Log::error('SMS sending to ' . $phone . ' failed. Cause: ' . $exception->getMessage());
			return false;
		}
	}

	public function send_sms_vas2nets($phone, $message){
		$messageUrl = str_replace('[MESSAGE]', $message, config('constants.message_settings.vas2nets_url'));
		$messageUrl = str_replace('[RECIPIENT]', $phone, $messageUrl);
		$messageUrl = str_replace('[SENDER]', config('constants.message_settings.sms_sender'), $messageUrl);

		$client = new \GuzzleHttp\Client();

		try{
        	$response = $client->request('GET', $messageUrl);
        	switch ($response->getBody()){
        		case '00':
            		Log::info('SMS Sent to '. $phone);
            		return true;
            		break;

        		case '41':
            		Log::error('SMS sending to ' . $phone . ' failed. Insufficient Credit');
            		return false;
            		break;

            	case '51':
            		Log::error('SMS sending to ' . $phone . ' failed. Gateway unreachable');
            		return false;
            		break;

            	case '52':
            		Log::error('SMS sending to ' . $phone . ' failed. System Error');
            		return false;
            		break;

            	default:
            		Log::error('SMS sending to ' . $phone . ' failed. ' . $response);
            		return false;

        	}

    	}
    	catch(Exception $exception){
    		Log::error('SMS sending to ' . $phone . ' failed. Cause: ' . $exception->getMessage());
            return false;

    	}
	}
	public function replacePlaceHolders($isTable, $type, $row, $message){

		if($isTable && $type == 'email'){

			$message = str_replace('[PARTNERID]', '%recipient.partner_id%', $message);
			$message = str_replace('[NAME]', '%recipient.name%', $message);
			$message = str_replace('[EMAIL]', '%recipient.email%', $message);
			$message = str_replace('[PHONE]', '%recipient.phone%', $message);
			$message = str_replace('[GENDER]', '%recipient.gender%', $message);
			$message = str_replace('[DATEOFBIRTH]', '%recipient.date_of_birth%', $message);
			$message = str_replace('[MARITALSTATUS]', '%recipient.marital_status%', $message);
			$message = str_replace('[OCCUPATION]', '%recipient.occupation%', $message);
			$message = str_replace('[BIRTHCOUNTRY]', '%recipient.birth_country%', $message);
			$message = str_replace('[RESIDENTIALCOUNTRY]', '%recipient.residential_country%', $message);
			$message = str_replace('[RESIDENTIALADDRESS]', '%recipient.residential_address%', $message);
			$message = str_replace('[POSTALADDRESS]', '%recipient.postal_address%', $message);

		}
		if($isTable && $type == 'sms'){

			$message = str_replace('[PARTNERID]', $row[0], $message);
			$message = str_replace('[NAME]', $row[2], $message);
			$message = str_replace('[EMAIL]', $row[3], $message);
			$message = str_replace('[PHONE]', $row[4], $message);
			$message = str_replace('[GENDER]', $row[5], $message);
			$message = str_replace('[DATEOFBIRTH]', $row[6], $message);
			$message = str_replace('[MARITALSTATUS]', $row[7], $message);
			$message = str_replace('[OCCUPATION]', $row[8], $message);
			$message = str_replace('[BIRTHCOUNTRY]', $row[9], $message);
			$message = str_replace('[RESIDENTIALCOUNTRY]', $row[10], $message);
			$message = str_replace('[RESIDENTIALADDRESS]', $row[11], $message);
			$message = str_replace('[POSTALADDRESS]', $row[12], $message);
		}
		else
		{
			$message = str_replace('[PARTNERID]', '', $message);
			$message = str_replace('[NAME]', '', $message);
			$message = str_replace('[EMAIL]', '', $message);
			$message = str_replace('[PHONE]', '', $message);
			$message = str_replace('[GENDER]', '', $message);
			$message = str_replace('[DATEOFBIRTH]', '', $message);
			$message = str_replace('[MARITALSTATUS]', '', $message);
			$message = str_replace('[OCCUPATION]', '', $message);
			$message = str_replace('[BIRTHCOUNTRY]', '', $message);
			$message = str_replace('[RESIDENTIALCOUNTRY]', '', $message);
			$message = str_replace('[RESIDENTIALADDRESS]', '', $message);
			$message = str_replace('[POSTALADDRESS]', '', $message);

		}

		return $message;
	}

	public function create_recipient_variable($table_row){

		$recipient_variable = [];

		foreach($table_row as $row){

			if(!filter_var($row[3], FILTER_VALIDATE_EMAIL))
				continue;
			$recipient_variable[$row[3]]['partner_id'] = $row[0];
			$recipient_variable[$row[3]]['name'] = $row[2];
			$recipient_variable[$row[3]]['email'] = $row[3];
			$recipient_variable[$row[3]]['phone'] = $row[4];
			$recipient_variable[$row[3]]['gender'] = $row[5];
			$recipient_variable[$row[3]]['date_of_birth'] = $row[6];
			$recipient_variable[$row[3]]['marital_status'] = $row[7];
			$recipient_variable[$row[3]]['occupation'] = $row[8];
			$recipient_variable[$row[3]]['birth_country'] = $row[9];
			$recipient_variable[$row[3]]['residential_country'] = $row[10];
			$recipient_variable[$row[3]]['residential_address'] = $row[11];
			$recipient_variable[$row[3]]['postal_address'] = $row[12];

			//$recipient_variable->push($recipient_value);
		}
		return $recipient_variable;
	}

	public function saveMessageLog($messageLog){

		Log::info('Saving Log');

    	$tokenController = new TokenController();
    	try{
    		$client_token = $tokenController->getClientCredential();
    	}
    	catch(RequestException $e){
    		Log::error(($e->getResponse()->getBody(true)->getContents()));
    			return response()->json(['message' => 'There was a problem during authentication'], 401);
    	}

		$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $client_token]]);

		try{
			$response = $client->request('POST', config('constants.api') .'/messages', [
			    'form_params' => [
			    	'partnerIdentifier' => $messageLog['partner_id'], 
			    	'enteredByUser' => $messageLog['username'],
			    	'sender' => $messageLog['sender'],
			    	'recipient' => $messageLog['recipient'],
			    	'emailSubject' => $messageLog['subject'], 
			    	'messageBody' => strip_tags($messageLog['message']),
			    	'messageStatus' => $messageLog['status'],
				]
			]);
		}
		catch(Exception $e){
			Log::error('Could not save message log: '  . $e->getResponse()->getBody()->getContents());
		}
	}


	private function send_message_to_table_data($tableData, $formData, $message, $message_progress){

		$recipient_variable = $this->create_recipient_variable($tableData);
	
		$messageBody = $this->replacePlaceHolders(true, 'email', null, $message);

       	$emailBody = view('email.email');
       	$emailBody = str_replace('[EMAILCONTENT]', $messageBody, $emailBody);

       	if(isset($formData['send_as_email'])){

          	Log::info('Sending email to batch');

		    SendBulkEmailJob::dispatch(session('user')['user_id'], session('user')['name'], $emailBody, $recipient_variable, $formData['subject'], $formData['sender']);   
    	}

    	if(isset($formData['send_as_sms'])){

		    SendBulkSmsJob::dispatch(session('user')['user_id'], session('user')['name'], $tableData, $message, $formData['sender']);   
    	}

		
	}

	private function send_message_to_list($send_list, $formData, $message, $message_progress, $job_identifier){

		$messageBody = $this->replacePlaceHolders(false, null, null, $message);

        $emailBody = view('email.email');
       	$emailBody = str_replace('[EMAILCONTENT]', $messageBody, $emailBody);

       	$email_recipients = [];
       	$sms_recipients = [];

        foreach($send_list as $send_to){

            if(filter_var($send_to, FILTER_VALIDATE_EMAIL)){
            	array_push($email_recipients, $send_to);
            }
            else{
            	array_push($sms_recipients,$send_to);  
            }
        }

        Log::info('Sending email to list');
        SendBulkListEmailJob::dispatch(session('user')['user_id'], session('user')['name'], $email_recipients, $emailBody, $formData['sender']);

        Log::info('Sending sms to list');
        SendBulklSmsListJob::dispatch(session('user')['user_id'], session('user')['name'], $email_recipients, $message, $formData['sender']);
	}

	public function cancel_job($job_to_cancel){
		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

		if(DB::table('jobs')->where('id', $job_to_cancel)->delete())
			return response()->json(['message' => 'success'], 200);
	}

	public function message_log(){

		if(!UserController::isUserLoggedIn())
    		redirect('/user-login');

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->get(config('constants.api') .'/messagelog/');
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
			$log =  json_decode($response->getBody()->getContents(), true)['data'];
			return view('message.log', ['logs' => $log]);

		if($response->getStatusCode() == 401)
			return json_decode(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve message information']);
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

    		$search_query = $search_query . $key . '=' . $request->$key . '&';

    	}

    	$search_query .= 'do-not-paginate';

    	//$search_query = substr($search_query, 0 , strlen($search_query) -1);

    	$tokenController = new TokenController();
    	$tokenController->validateToken();

    	$client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer ' . $tokenController->getUserToken()]]);

    	try{
			$response = $client->request('GET', config('constants.api') .'/search-message-log' . $search_query);
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

			$messages = json_decode($response->getBody()->getContents(), true)['data'];

			if(count($messages) > 0)

				return view('message.search_message_log_result', ['messages' => $messages]);
			else
				return response('No record found');

		if($response->getStatusCode() == 401)
			return response()->json(['message' => 'Sorry, you are not authorized to view this page']);
		
		return response()->json(['message' => 'Could not retrieve user information']);
    	
    }

}
