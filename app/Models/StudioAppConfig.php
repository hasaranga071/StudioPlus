<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioAppConfig extends Model
{
    use HasFactory;

    protected $table = 'studioappconfig'; // Define table name if different from Laravel's convention

    protected $primaryKey = 'appconfigkey'; // Custom primary key

    public $timestamps = false; // Disable timestamps if not using created_at & updated_at

    protected $fillable = [
        'studiokey',
        'softcopyunitprice',
        'createdtime'
    ];

    protected $casts = [
        'createdtime' => 'datetime',
    ];

    // Define relationship with Studio model
    public function appconfig()
    {
        return $this->belongsTo(Studio::class, 'appconfigkey', 'appconfigkey');
    }
}
