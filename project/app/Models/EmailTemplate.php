<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['email_type', 'email_subject', 'email_body', 'whatsapp_body','sms_body','status'];
    public $timestamps = false;
}
