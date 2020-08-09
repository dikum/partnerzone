<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Authentication\TokenController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(){

    	if(!UserController::isUserLoggedIn())
    	   return redirect('/user-login');
        
		$userController =  new UserController();
		$user = $userController->getUser(session('user')['user_id']);

        if(isset($user->original['message']))
            return 'Could not load dashboard. Please contact your administrator';

		return view('dashboard', ['user' => explode(' ', $user->data->fullname)[0]]);
    	
    	
    	
    }
}
