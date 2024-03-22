<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanEmiCharges extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_loan_id',
        'emi_charges',
        'next_installment',
        'prev_installment',
    ];

    public function loans(){
        return $this->belongsTo(UserLoan::class);
    }
}
