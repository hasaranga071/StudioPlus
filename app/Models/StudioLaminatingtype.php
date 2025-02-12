<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioLaminatingtype extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioLaminatingtypes';

    // Specify the primary key
    protected $primaryKey = 'lamtypekey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for these fields
    protected $fillable = [
        'laminatetype',
        'unitcost',
    ];
}
