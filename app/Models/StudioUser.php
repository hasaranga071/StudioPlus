<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioUser extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'StudioUsers';

    // Specify the primary key (optional if 'id')
    protected $primaryKey = 'userkey';

    // Disable auto-increment if not applicable
    public $incrementing = true;

    // Set the primary key type
    protected $keyType = 'int';

    // Enable/Disable timestamps
    public $timestamps = true;

    // If using different column names for created/updated timestamps
    const CREATED_AT = 'createdtime';
    const UPDATED_AT = 'updatedtime';

    // Mass assignable attributes
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
}
