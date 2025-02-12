<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioOrderItemMapFR extends Model
{
    use HasFactory;

    // Specify the custom table name
    protected $table = 'StudioOrderItemMapFR';

    // Specify the primary key
    protected $primaryKey = 'frorderitemmapkey';

    // Disable timestamps (if the table does not have `created_at` and `updated_at`)
    public $timestamps = false;

    // Allow mass assignment for the following fields
    protected $fillable = [
        'orderkey',
        'framesizekey',
        'edittypekey',
        'quantity',
        'lamtypekey',
    ];

    public function order()
    {
        return $this->belongsTo(StudioOrder::class, 'orderkey');
    }

    public function frameSize()
    {
        return $this->belongsTo(StudioFramesize::class, 'framesizekey');
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
