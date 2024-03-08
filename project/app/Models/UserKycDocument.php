<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKycDocument extends Model
{
    use HasFactory;
    protected $table = 'users_kyc_document';

    protected $fillable = ['user_id','aadhaar_front','aadhaar_back','aadhaar_no','pan_front','pan_back','pan_no','api_response_aadhar','api_response_pan','current_address','api_response_banking','api_response_digilocker_status','voter_no','api_response_aadhar_front_img','api_response_aadhar_back_img','api_response_pan_front_img','api_response_voter_front_img','api_response_voter_back_img','addressproof','api_response_address','api_response_banking_status','api_response_banking_details','bank_details_file_name','office_current_address'];
}
