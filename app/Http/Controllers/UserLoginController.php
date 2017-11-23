<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registration;
use Symfony\Component\Debug;
use Illuminate\Support\Facades\Hash;
use App\UserLoginProcess;


/**
 * Class RegistrationController
 * @package App\Http\Controllers
 */
class UserLoginController extends Controller {

    private $response; // all return json responses
    private $sysData; // all users data
    private $userData; // user input data

    /**
     * RegistrationController constructor.
     */
    public function __construct() {

        try {
            // Get all registered users
            $sysData = json_decode(Registration::getUsers());

            if(isset($sysData[0])) {

                // store in global property
                $this->sysData = $sysData[0];
            }
        } catch(Exception $e) {
            report($e);

            return false;
        }

    }

    /**
     * @method CheckUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoginData(Request $request) {

        $this->userData = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        $validator = UserLoginProcess::validation($this->userData);

        if(isset($validator->original['errorCode'])) {
            $this->response = $validator->original;
        } else {
            $this->response = $this->findUser();
        }

        return $this->response;
    }

    /**
     * @method FindUser
     * @return \Illuminate\Http\JsonResponse
     */
    private function findUser() {
        
        if($this->userData['username'] === $this->sysData->username):
            $this->response = $this->checkPassword();
        else:
            $this->response = [
                'errorCode' => 300,
                'message' => "User does not exist, please register.",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
        endif;

        return response()->json($this->response);
    }

    /**
     * @method CheckPassword
     * @return \Illuminate\Http\JsonResponse
     */
    private function checkPassword(){

        if(Hash::check($this->userData['password'], $this->sysData->password)):
            return $this->registrationEndPoint();
        else:
            $this->response = [
                'errorCode' => 301,
                'message' => "Username and Password do not match!.",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
        endif;

        return response()->json($this->response);
    }

    private function registrationEndPoint() {

        $this->response = [
            'responseCode' => 201,
            'message' => 'User found', 
            'userInfo' => $this->sysData,
            'userToken' => Hash::make('paypa_token')
        ];

        return response()->json($this->response);
    }

    public function validatePassword() {

    }
    
}
