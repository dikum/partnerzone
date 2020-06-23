<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\User\UserController;

class DashboardController extends Controller
{
    public function index(){
    	if(UserController::isUserLoggedIn()){
    		return view('dashboard');
    	}

    	session(['intended' => url()->full()]);
    	return redirect('/user-login');
    }
}
