<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    public static function isUserLoggedIn(){
    	if(Cookie::get('user'))
    		return true;
    	
    	return redirect('/user-login');
    }
}
