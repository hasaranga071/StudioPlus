<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSubframesize extends Model
{
    use HasFactory;

    protected $table = 'studiosubframesizes'; // Ensure this matches the database table name
    protected $primaryKey = 'subframesizekey'; // Set the correct primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'framesize',
        'unitprice',
    ];
}
