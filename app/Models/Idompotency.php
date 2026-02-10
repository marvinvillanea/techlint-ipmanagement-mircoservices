<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idompotency extends Model
{
    //

    protected $table = 'idompotencies';

    protected $fillable = ['uniqueid'];

    public $timestamps = false;
    
}
