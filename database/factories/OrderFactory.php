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
class OrderFactory extends Factory
{
    use HasIdentifier;

    public function definition():array
    {

        return [
            'total_price' => mt_rand(30, 100),
            'user_id' => User::get()->random(),
            'completed_at' => now()
        ];
    }
}
