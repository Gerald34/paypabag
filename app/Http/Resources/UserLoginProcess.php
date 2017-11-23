<?php

namespace App;

use Illuminate\Http\Resources\Json\Resource;
use Respect\Validation\Validator as secure;
/**
 * Class UserLoginProcess
 * @package App\Http\Resources
 */
class UserLoginProcess extends Model {

	private $userData;

    public static function validation($userData) {
    	echo secure::startsWith('@')->validate($userData['username']);
    }

}