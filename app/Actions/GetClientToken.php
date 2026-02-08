<?php
namespace App\Actions;

use App\Models\Client;

class GetClientToken
{
    public function execute($name)
    {

        $client = Client::where('name', $name)->first();
        return !$client ? false : $client;
        
    }
}