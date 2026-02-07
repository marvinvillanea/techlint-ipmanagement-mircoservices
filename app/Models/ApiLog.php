<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    //
    protected $fillable = [
        'ip',
        'user_agent',
        'method',
        'url',
        'request_body',
        'response_body',
        'status_code',
        'user_id'
    ];
}
