<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    // Table name (if it differs from 'studios')
    protected $table = 'studios';

    // Primary key column
    protected $primaryKey = 'studiokey';

    // Auto-incrementing primary key
    public $incrementing = true;

    // Primary key data type
    protected $keyType = 'int';

    // Timestamps (created_at, updated_at)
    public $timestamps = true;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'studioname',
        'owneruserkey',
        'description',
        'address',
        'location',
        'isactive',
        'createdtime',
        'updatedtime',
    ];

    // Define relationships (e.g., Studio belongs to a User)
    public function user()
    {
        return $this->belongsTo(StudioUser::class, 'owneruserkey', 'userkey');
    }
}
