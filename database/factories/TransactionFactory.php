<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TransactionFactory extends Factory
{

    public function definition():array
    {
        return [
            'amount' => mt_rand(10, 70),
            'completed_at' => now(),
            'user_id' => User::get()->random()->id,
            'order_id' => Order::get()->random()->id,
            'transaction_type' => TransactionType::get()->random()->id,
        ];
    }
}
