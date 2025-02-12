<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderType extends Model
{
    use HasFactory;

    protected $table = 'studioordertypes'; // Explicitly specify the table name
    protected $primaryKey = 'ordertypekey'; // Define primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'ordertype',
        'description'
    ];
}
