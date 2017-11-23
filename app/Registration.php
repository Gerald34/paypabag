<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
    public static function getUsers() {
        $allUsers = DB::table('paypa_users')->get();
        //echo "My name is {$username}";
        return $allUsers;
    }

    // public function toArray($request) {
        
    //     return [
    //         'id' => $this->id,
    //         'name' => $this->name,
    //         'surname' => $this->surname,
    //         'email' => $this->email,
    //         'gender' => $this->gender,
    //         'birth_date' => $this->birth_date,
    //         'password' => $this->password,
    //         'permissions'=> $this->permissions,
    //         'cellphone' => $this->cellphone
    //     ];
    // }
}
