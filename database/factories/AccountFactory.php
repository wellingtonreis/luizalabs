<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        $transaction = Transaction::factory()->create();

        return [
            'number_account' => $this->faker->unique()->randomNumber(8),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
            'created_at' => now(),
            'id_transaction' => $transaction->id,
        ];
    }
}
