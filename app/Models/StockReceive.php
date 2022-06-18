<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'receive_no',
        'receive_date',
        'item_id',
        'supplier_id',
        'quantity',
        'price',
        'used_qty',
        'entry_by'
    ];

}
