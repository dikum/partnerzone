<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    public function getClientCredential(){

    	$client = new \GuzzleHttp\Client();
		$response = $client->request('POST', 'http://partnersoft.test/oauth/token', [
		    'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => 5,
		        'client_secret' => 'UgNlFTi3hnvtzJrEWW7x5PtlKBtk8R6zAzmFalOr',
		        'scope' => '*'
		   	]
		]);

		return json_decode((string) $response->getBody(), true)['access_token'];
    }
}
