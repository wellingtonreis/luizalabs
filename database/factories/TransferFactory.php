<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    protected $model = Transfer::class;
    
    public function definition(): array
    {
        $originAccount = Account::inRandomOrder()->first();
        $destinationAccount = Account::where('number_account', '!=', $originAccount->number_account)
                                     ->inRandomOrder()
                                     ->first();

        $transferAmount = $this->faker->randomFloat(2, 1, $originAccount->balance);

        $originAccount->decrement('balance', $transferAmount);
        $destinationAccount->increment('balance', $transferAmount);

        return [
            'number_account_origin' => $originAccount->number_account,
            'number_account_destination' => $destinationAccount->number_account,
            'amount' => $transferAmount,
            'type' => $this->faker->randomElement(['deposit', 'withdraw', 'transfer']),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
        ];
    }
}
