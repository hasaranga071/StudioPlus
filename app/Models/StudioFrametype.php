<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioFrametype extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioFrametypes';

    // Specify the primary key
    protected $primaryKey = 'frametypekey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'frametype',
    ];
}
