<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\User\UserController;

class DashboardController extends Controller
{
    public function index(){

    	if(UserController::isUserLoggedIn())
    	{
    		$userController =  new UserController();
    		$user = $userController->getUser(session('user'));
    		return view('dashboard', ['user' => explode(' ', $user->data->fullname)[0]]);
    	}
    	
    	return redirect('/user-login');
    }
}
