<?php

namespace App\Http\Controllers;

// Application Model
use App\AppUsers;

//System Classes
use Illuminate\Http\Request;
use Symfony\Component\Debug;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

// Processors
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
    public $log; // system logger

    /**
     * RegistrationController constructor.
     */
    public function __construct() {
        // create a log channel
        $this->log = new Logger('Login');
        $this->log->pushHandler(new RotatingFileHandler('/var/log/paypabg_logs/paypa_login.log', Logger::INFO));
        $this->log->pushHandler(new FirePHPHandler());
    }

    /**
     * @method CheckUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoginData(Request $request) {
        $jsonData = file_get_contents("/var/www/html/paypa_api/responsecodes.json");

        $userData = [
            'username' => strip_tags(trim($request->input('username'))),
            'password' => strip_tags(trim($request->input('password')))
        ];

        $validator = UserLoginProcess::loginValidation($userData);

        if(isset($validator['errorCode'])) {
            $this->response = $validator;

            // add records to the log
            // Log Warnings and Failed Response
            $this->log->error(
                "Failed Login @ {$_SERVER['REMOTE_ADDR']} by {$userData['username']}",
                ["response " => $this->response],
                $this->response['message']
            );
        } else {
            $this->response = $this->findUser($userData);

            // add records to the log
            if(isset($this->response['responseCode'])):

                // Log Success Response
                $this->log->info(
                    "Successful Login @ {$_SERVER['REMOTE_ADDR']} by {$userData['username']}",
                    [
                        "input" => [
                            "username" => $userData['username'],
                            "password" => $userData['password']
                        ],
                        "output" => $this->response
                    ],
                    $this->response['message']
                );
            else:

                // Log Warnings and Failed Response
                $this->log->warning(
                    "Failed Login @ {$_SERVER['REMOTE_ADDR']} by {$userData['username']}",
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
     * @return Array
     */
    private function findUser(array $userData) {
        $this->sysData = AppUsers::where('username',$userData['username'])->first();

        if(isset($this->sysData) || !empty($this->sysData)):
            $this->response = $this->checkPassword($userData);
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
     * @return Array
     */
    private function checkPassword(array $userData){

        if(Hash::check($userData['password'], $this->sysData->password)):
            $this->response = $this->loginEndPoint($userData);
        else:
            $this->response = [
                'errorCode' => 301,
                'input' => [
                    'username' => $userData['username'],
                    'password' => $userData['password']
                ],
                'message' => "Username and Password do not match!.",
                'userInfo' => NULL,
                'userToken' => NULL
            ];
        endif;

        return $this->response;
    }

    private function loginEndPoint(array $userData){
        $current_time = Carbon::now();
        $userID = $this->sysData->id; 
        
        $this->response = [
            'responseCode' => 201,
            'message' => 'User found', 
            'userInfo' => $this->sysData,
            'userToken' => Hash::make("paypa_token{$this->sysData->username}{$this->sysData->password}"),
            'current_login_activity' => $current_time,
            'last_login_time' => $this->sysData->last_login_at
        ];

        $user_login_time = $this->response['current_login_activity'];
        self::updateLoginTime($user_login_time, $userID);
        // Update LOgin Time
        return $this->response;
    }

    public static function updateLoginTime($user_login_time, $userID) {
        $login_time = AppUsers::find($userID);
        $login_time->last_login_at = $user_login_time;
        $login_time->save();
    }
    
}

//thulani nxumalo
//063 006 8548