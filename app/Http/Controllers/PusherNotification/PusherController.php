<?php
namespace App\Http\Controllers\PusherNotification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class PusherController extends Controller
{
    const API_KEY = '1079761';
    const KEY = '1153595b89e97452da79';
    const SECRET = '9a301a129084b19a4859';
    const CLUSTER = 'ap2';

    public function send_message_progress($user_id, $progress, $message_group, $message_job_identifier){
		$options = array(
		    'cluster' => PusherController::CLUSTER,
		    //'useTLS' => true
		);

	  	$pusher = new \Pusher\Pusher(
		    PusherController::KEY,
		    PusherController::SECRET,
		    PusherController::API_KEY,
	    	$options
	  	);

	  	$data = [
	  		'user_id' => $user_id,
	  		'progress' => $progress,
	  		'message_group' => $message_group, 
	  		'message_job_identifier' => $message_job_identifier,
	  	];

	  	try{
  			$pusher->trigger('partnerzone', 'message_progress', $data);
  		}
  		catch(Exception $e){
  			Log::debug('Pusher faced some issues posting notifications: ' . $e->getMessage());
  		}

    }


}
