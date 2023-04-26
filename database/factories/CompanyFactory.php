<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use App\Traits\HasIdentifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CompanyFactory extends Factory
{
    use HasIdentifier;

    public function definition():array
    {

        $card = new Card;

        return [
            'name' => fake()->word(),
        ];
    }
}
