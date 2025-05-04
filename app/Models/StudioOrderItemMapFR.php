<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderItemMapFR extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'studioorderitemmapfr';

    // Specify the primary key
    protected $primaryKey = 'frorderitemmapkey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'orderkey',
        'jobid',
        'frametypekey',
        'framesizekey',
        'subframesizekey',
        'subframetypekey',
        'totalcost',
        'quantity',
        'iscompleted',
    ];

    public function order()
    {
        return $this->belongsTo(StudioOrder::class, 'orderkey');
    }

    public function frameSize()
    {
        return $this->belongsTo(StudioFramesize::class, 'framesizekey');
    }

    public function frameType()
    {
        return $this->belongsTo(StudioFrametype::class, 'frametypekey');
    }

    public function subframeSize()
    {
        return $this->belongsTo(StudioSubframesize::class, 'subframesizekey');
    }

    public function subframeType()
    {
        return $this->belongsTo(StudioSubframetype::class, 'subframetypekey');
    }

}
