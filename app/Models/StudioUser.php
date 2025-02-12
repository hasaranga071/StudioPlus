<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StudioUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'studiousers'; // Specify the custom table name
    protected $primaryKey = 'userkey'; // Specify the custom primary key

    public $timestamps = false; // Disable default Laravel timestamps

    protected $fillable = [
        'studiokey',
        'username',
        'usertypekey',
        'email',
        'password',
        'rolekey',
        'phonenumber',
        'address',
        'isactive',
        'profileimage',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
