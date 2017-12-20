<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class AppUsers
 * @package App
 * 
 * @property int id
 * @property int paypa_id
 * @property string username
 * @property string password
 * @property string name
 * @property string surname
 * @property string email
 * @property string gender
 * @property string birth_date
 * @property string street_number
 * @property string street_name
 * @property string suburb
 * @property string city
 * @property string province
 * @property string country
 * @property Carbon created_at
 * @property Carbon last_login_at
 * @property Carbon logout_at
 * @property string token_life
 */
class AppUsers extends Model
{
    //

    protected $table = 'paypa_users';
    public $timestamps = false;
}
