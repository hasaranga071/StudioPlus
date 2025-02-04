<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioCustomer extends Model
{
    use HasFactory;

    protected $table = 'studiocustomers'; // Define table name if different from Laravel's convention

    protected $primaryKey = 'customerkey'; // Custom primary key

    public $timestamps = false; // Disable timestamps if not using created_at & updated_at

    protected $fillable = [
        'studiokey',
        'username',
        'email',
        'phonenumber',
        'address',
        'createdtime',
    ];

    protected $casts = [
        'createdtime' => 'datetime',
    ];

    // Define relationship with Studio model
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studiokey', 'studiokey');
    }
}
