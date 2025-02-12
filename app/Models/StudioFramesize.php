<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioFramesize extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioFramesizes';

    // Specify the primary key
    protected $primaryKey = 'framesizekey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for these fields
    protected $fillable = [
        'ordertypeitemkey',
        'frametypekey',
        'size',
        'unitprice',
    ];
}
