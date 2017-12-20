<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/** 
 * @property id
 * @property user_id
 * @property current_login_time
 * @property last_login_time
*/
class UserLogin extends Model
{
    //
    protected $table = "user_logins";
    public $timestamps = false;
}
