<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 * 
 * @property int id
 * @property string name
 * @property string surname
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 * @property string username
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function users(){
        return $this->hasMany(User::class, 'author_id', 'id');
    }
}
