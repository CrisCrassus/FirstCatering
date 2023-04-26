<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\CardType;
use App\Models\User;
use App\Traits\HasIdentifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CardFactory extends Factory
{
    use HasIdentifier;

    public function definition():array
    {

        $card = new Card;

        return [
            'identifier' => $card->generateIdentifier($card, 16),
            'card_type' => CardType::get()->random()->id,
            'last_used' => now(),
            'user_id' => User::get()->random(),
        ];
    }
}
