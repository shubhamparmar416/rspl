<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKycDocument extends Model
{
    use HasFactory;
    protected $table = 'users_kyc_document';

    protected $fillable = ['user_id','aadhaar_front','aadhaar_back','aadhaar_no','pan_front','pan_back','pan_no','api_response_aadhar','api_response_pan'];
}
