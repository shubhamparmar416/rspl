<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCharges extends Model
{
    use HasFactory;
    protected $table = 'loan_charges';

    protected $fillable = [
        'name',
        'type',
        'gst_applicable',
        'gst_percentage',
        'amt_type',
        'amt_value',
    ];

}
