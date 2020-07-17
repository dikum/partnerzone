<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    public function make_request($endpoint){

    	//TODO: Let all controllers that will make requests to the api extend this controller and make use of the function
    }
}
