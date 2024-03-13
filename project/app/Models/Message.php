<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'message_integration';

    protected $fillable = [
        'sms',
        'whatsapp',
        'email',
        'status',
    ];

}
