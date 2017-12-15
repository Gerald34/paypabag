<?php

namespace App;

use Illuminate\Http\Resources\Json\Resource;
use Respect\Validation\Validator as secure;
/**
 * Class UserLoginProcess
 * @package App\Http\Resources
 */
class UserLoginProcess {

    public static function loginValidation($userData) {
    	$response = array();

    	/**
    	 * @validation Username
    	 * @return json
    	 **/
    	if(secure::stringType()->notEmpty()->validate($userData['username']) == false) {
    		$response = [
                'errorCode' => 601,
                'message' => "Username required",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
    	}

    	/**
    	 * @validation Password
    	 * @return json
    	 **/
    	if(secure::stringType()->noWhitespace()->notEmpty()->validate($userData['password']) == false) {
    		$response = [
                'errorCode' => 602,
                'message' => "Password required",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
    	} elseif(secure::stringType()->length(null, 4)->validate($userData['password']) == false) {
    		$response = [
                'errorCode' => 604,
                'message' => "Password exceeds maximum characters",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
    	} elseif(secure::stringType()->length(4, null)->validate($userData['password']) == false) {
    		$response = [
                'errorCode' => 606,
                'message' => "Password needs to be atleast 4 characters",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
    	}

    	return response()->json($response);
    }

    public static function registrationValidation($request) {

        $userData = [
            'username' => trim($request->input('username')),
            'password' => trim($request->input('password')),
            'paypa_id' => trim($request->input('paypa_id')),
            'name' => trim($request->input('name')),
            'surname' => trim($request->input('surname')),
            'email' => trim($request->input('email')),
            'gender' => trim($request->input('gender')),
            'birth_date' => trim($request->input('birth_date')),
            'street_number' => trim($request->input('street_number')),
            'street_name' => trim($request->input('street_name')),
            'suburb' => trim($request->input('suburb')),
            'city' => trim($request->input('city')),
            'province' => trim($request->input('province')),
            'country' => trim($request->input('country'))
        ];

        return $userData;
    }

}