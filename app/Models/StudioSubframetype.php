<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSubframetype extends Model
{
    use HasFactory;

    protected $table = 'studiosubframetypes'; // Ensure this matches the database table name
    protected $primaryKey = 'subframetypekey'; // Set the correct primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'subframetype',
    ];
}
