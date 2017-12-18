<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registration;
use Symfony\Component\Debug;
use Illuminate\Support\Facades\Hash;
use App\UserLoginProcess;

// Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\RotatingFileHandler;

/**
 * Class RegistrationController
 * @package App\Http\Controllers
 */
class UserLoginController extends Controller {

    private $response; // all return json responses
    private $sysData; // all users data
    private $userData; // user input data
    public $log; // system logger

    /**
     * RegistrationController constructor.
     */
    public function __construct() {
        // create a log channel
        $this->log = new Logger('Login');
        $this->log->pushHandler(new RotatingFileHandler('/var/log/paypabg_logs/paypa_login.log', Logger::INFO));
        $this->log->pushHandler(new FirePHPHandler());

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
        $jsonData = file_get_contents("/var/www/html/paypa_api/responsecodes.json");

        $this->userData = [
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];

        $validator = UserLoginProcess::loginValidation($this->userData);

        if(isset($validator->original['errorCode'])) {
            $this->response = $validator->original;

            // add records to the log
            $this->log->error($this->response);
        } else {
            $this->response = $this->findUser();
//            var_dump($this->response);
//            exit;
            // add records to the log
            if(isset($this->response['responseCode'])):

                // Log Success Response
                $this->log->info(
                    "Successful Login @ {$_SERVER['REMOTE_ADDR']} by {$this->userData['username']}",
                    [
                        "input" => [
                            "username" => $this->userData['username'],
                            "password" => $this->userData['password']
                        ],
                        "output" => $this->response
                    ],
                    $this->response['message']
                );
            else:

                // Log Warnings and Failed Response
                $this->log->warning(
                    "Failed Login @ {$_SERVER['REMOTE_ADDR']} by {$this->userData['username']}",
                    ["response " => $this->response],
                    $this->response['message']
                );
            endif;
        }

        // Return endPoint Login Process
        return response()->json($this->response);
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

        return $this->response;
    }

    /**
     * @method CheckPassword
     * @return \Illuminate\Http\JsonResponse
     */
    private function checkPassword(){

        if(Hash::check($this->userData['password'], $this->sysData->password)):
            $this->response = $this->loginEndPoint();
        else:
            $this->response = [
                'errorCode' => 301,
                'input' => [
                    'username' => $this->userData['username'],
                    'password' => $this->userData['password']
                ],
                'message' => "Username and Password do not match!.",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
        endif;

        return $this->response;
    }

    private function loginEndPoint() {

        $this->response = [
            'responseCode' => 201,
            'message' => 'User found', 
            'userInfo' => $this->sysData,
            'userToken' => Hash::make("paypa_token{$this->userData['username']}{$this->userData['password']}")
        ];

        return $this->response;
    }

    public function validatePassword() {

    }
    
}

//thulani nxumalo
//063 006 8548