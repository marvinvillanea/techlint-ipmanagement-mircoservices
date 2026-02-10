<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => '000IP-TECHLINT000',       // random company name
            // 'client_token' => Str::random(60),    
            'client_token' => 'St0uVEktCxF8kr3nRkeHCqFotKBLoqqGhMhRGvwiIA0DJcJeEj2VnauzjvzI',    
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
