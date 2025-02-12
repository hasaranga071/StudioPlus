<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrder extends Model
{
    use HasFactory;

    protected $table = 'StudioOrders'; // Define the table name
    protected $primaryKey = 'orderkey'; // Primary key
    public $timestamps = false; // Disable timestamps (handled manually)

    protected $fillable = [
        'studiokey',
        'ordertypekey',
        'customerkey',
        'isurgent',
        'updateduserkey',
        'createduserkey',
        'totalcost',
        'paidcost',
        'discount',
        'salestatus',
        'createdtime',
        'updatedtime',
        'deliverydate',
        'remarks'
    ];

    // Define relationships
    public function studio()
    {
        return $this->belongsTo(Studio::class, 'studiokey');
    }

    public function orderType()
    {
        return $this->belongsTo(StudioOrderType::class, 'ordertypekey');
    }

    public function customer()
    {
        return $this->belongsTo(StudioCustomer::class, 'customerkey');
    }
}
