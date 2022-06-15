<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    protected $fillable = [
        'requisition_no',
        'requisition_date',
        'description',
        'status',
        'entry_by',
        'updated_by'
    ];
}
