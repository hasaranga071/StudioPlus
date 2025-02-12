<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderTypeItemMap extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioOrderTypeItemMap';

    // Specify the primary key
    protected $primaryKey = 'ordertypeitemkey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at` columns)
    public $timestamps = false;

    // Allow mass assignment for these columns
    protected $fillable = [
        'ordertypekey',
        'itemname',
        'unitprice',
    ];

    public function orderType()
    {
        return $this->belongsTo(StudioOrderType::class, 'ordertypekey');
    }

    public function items()
    {
        return $this->hasMany(StudioOrderTypeItemMap::class, 'ordertypekey');
    }

}
