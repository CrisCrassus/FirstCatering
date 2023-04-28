<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\CardTypes;
use App\Enums\Roles;
use App\Enums\TransactionTypes;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TransactionType::create([
            'type' => TransactionTypes::PURCHASE->value,
        ]);

        TransactionType::create([
            'type' => TransactionTypes::TOPUP->value,
        ]);

        CardType::create([
            'type' => CardTypes::EXISTING->value,
        ]);

        CardType::create([
            'type' => CardTypes::STANDARD->value,
        ]);

        Role::create([
            'title' => Roles::ADMIN->value,
        ]);

        Role::create([
            'title' => Roles::STANDARD->value,
        ]);

        User::factory(5)->create();
        Card::factory(5)->create();
        Company::factory(5)->create();
        Product::factory(40)->create();
        Order::factory(40)->create();
        Transaction::factory(30)->create();

        //OrderProduct
        for ($i = 0; $i < 50; $i++) {
            DB::table('order_product')->insert(
                [
                    'order_id' => Order::get()->random()->id,
                    'product_id' => Product::get()->random()->id,
                ]
            );
        }



        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
