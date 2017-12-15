<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Registration extends Model
{

    protected $fillable = ['username', 'password','paypa_id'];

    use HasApiTokens, Notifiable;
    public static function getUsers() {
        $allUsers = DB::table('paypa_users')->get();

        return $allUsers;
    }
}
