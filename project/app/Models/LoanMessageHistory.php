<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanMessageHistory extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'loan_message_history';

    protected $fillable = ['user_id','loan_id','message','role'];


}
