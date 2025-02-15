<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderItemMapSS extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioOrderItemMapSS';

    // Specify the primary key
    protected $primaryKey = 'ssorderitemmapkey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'orderkey',
        'ordertypeitemkey',
        'edittypekey',
        'lamtypekey',
        'softcopyquantity',
        'hardcopyquantity',
        'totalcost',
    ];

    public function order()
    {
        return $this->belongsTo(StudioOrder::class, 'orderkey');
    }

    public function orderTypeItem()
    {
        return $this->belongsTo(StudioOrderTypeItemMap::class, 'ordertypeitemkey');
    }

    public function editType()
    {
        return $this->belongsTo(StudioEdittype::class, 'edittypekey');
    }

    public function lamType()
    {
        return $this->belongsTo(StudioLaminatingtype::class, 'lamtypekey');
    }
}
