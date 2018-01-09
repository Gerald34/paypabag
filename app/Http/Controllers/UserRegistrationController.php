<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\UserLoginProcess;

class UserRegistrationController extends Controller
{
	private $userData;

    public function getRegistrationData(Request $request) {

    	try{

    		$this->userData = UserLoginProcess::registrationValidation($request);
    		
    	}catch(EXception $e){
    		return back($e);
    	}

    	dump($this->userData);
    }
}
