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
    	} elseif($userData['username'][0] !== '@') {
            $response = [
                'errorCode' => 607,
                'message' => "Username should start with the '@' character",
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

    	return $response;
    }
    
    public static function registrationValidation($request) {

        $userData = [
            'username' => strip_tags(trim($request->input('username'))),
            'password' => strip_tags(trim($request->input('password'))),
            'paypa_id' => strip_tags(trim($request->input('paypa_id'))),
            'name' => strip_tags(trim($request->input('name'))),
            'surname' => strip_tags(trim($request->input('surname'))),
            'email' => strip_tags(trim($request->input('email'))),
            'gender' => strip_tags(trim($request->input('gender'))),
            'birth_date' => strip_tags(trim($request->input('birth_date'))),
            'street_number' => strip_tags(trim($request->input('street_number'))),
            'street_name' => strip_tags(trim($request->input('street_name'))),
            'suburb' => strip_tags(trim($request->input('suburb'))),
            'city' => strip_tags(trim($request->input('city'))),
            'province' => strip_tags(trim($request->input('province'))),
            'country' => strip_tags(trim($request->input('country')))
        ];

        return $userData;
    }

}