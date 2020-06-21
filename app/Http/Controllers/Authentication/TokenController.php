<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function getClientCredential(){

    	$client = new \GuzzleHttp\Client();
		$response = $client->request('POST', 'http://partnersoft.test/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => 2,
		        'client_secret' => 'OuThg5EF04lPh0ivZsolh0WvpZ9oc3H8cPiVihGC',
		        'scope' => '*',
		   	]
		]);

		return json_decode((string) $response->getBody(), true)['access_token'];
    }
}
