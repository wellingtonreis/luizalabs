<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $account = AccountFactory::new()->create();

        return [
            'number_account' => $account->number_account,
            'type' => $this->faker->randomElement(['deposit', 'withdraw', 'transfer']),
            'value' => $this->faker->randomFloat(2, 0, 10000),
            'created_at' => now(),
            'description' => $this->faker->sentence(6),
        ];
    }
}