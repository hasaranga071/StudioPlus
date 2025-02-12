<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderItemMapEC extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioOrderItemMapEC';

    // Specify the primary key
    protected $primaryKey = 'ecorderitemmapkey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'orderkey',
        'originalorderkey',
        'ordertypeitemkey',
        'edittypekey',
        'lamtypekey',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(StudioOrder::class, 'orderkey');
    }

    public function originalOrder()
    {
        return $this->belongsTo(StudioOrder::class, 'originalorderkey');
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
