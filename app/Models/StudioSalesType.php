<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioSaleType extends Model
{
    use HasFactory;

    protected $table = 'studiosaletypes'; // Explicitly specify the table name
    protected $primaryKey = 'saletypekey'; // Define primary key
    public $timestamps = false; // Disable timestamps if not needed

    protected $fillable = [
        'salestype',
        'description'
    ];
}
