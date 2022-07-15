<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 2022-07-13   Initial creation. 
 * Temporary class to store mail model. 
 */
class Emails extends Model
{
    // 
    protected $fillable = [
        'email_from',
        'email_to',
        'email_type',
        'email_message',
        'email_sender_ip_address',
        'email_request_type',
        'email_created_at',
        'email_sent_at',
        'email_is_succesfully_sent',
        'email_sender_driver',
        'final_email_sender',
    ];
}
