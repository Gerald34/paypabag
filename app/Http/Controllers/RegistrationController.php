<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registration;
use Symfony\Component\Debug;
use Illuminate\Support\Facades\Hash;

/**
 * Class RegistrationController
 * @package App\Http\Controllers
 */
class RegistrationController extends Controller {
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
    public function checkUsers(Request $request) {
        
        $this->userData = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        return $this->findUser();
    }

    /**
     * @method FindUser
     * @return \Illuminate\Http\JsonResponse
     */
    private function findUser() {
        
        if($this->userData['username'] === $this->sysData->username) {
            $this->response = $this->checkPassword();
        }

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
                'type' => false,
                'message' => "Username and Password do not match!."
            ];
        endif;

        return response()->json($this->response);
    }

    private function registrationEndPoint() {

        $this->response = [
            'type' => true,
            'userInfo' => $this->sysData
        ];

        return response()->json($this->response);
    }
    
}
