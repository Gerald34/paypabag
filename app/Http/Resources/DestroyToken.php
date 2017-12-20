<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\AppUsers;

class DestroyToken
{
    public static function destroyToken(int $userID){
        $app = AppUsers::find($userID);
        $app->token_life = '';
        $app->save();
    }
}

