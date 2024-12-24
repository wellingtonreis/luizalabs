<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use Faker\Factory;
use App\Models\Account;

class WithdrawControllerTest extends TestCase
{
    private $faker;
    private $datetime;
    private $account;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $this->datetime = now()->format('Y-m-d H:i:s');
        $this->account = Account::factory()->create(
            [
                'number_account' => $this->faker->unique()->randomNumber(8),
                'balance' => $this->faker->randomFloat(2, 0, 10000),
                'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
                'created_at' => $this->datetime,
            ]
        )->first();
    }

    public function test_withdraw_successful()
    {
        $response = $this->postJson('/api/v1/withdraw', [
            'numberAccountOrigin' => $this->account->number_account,
            'value' => 1.00,
            'type' => 'withdraw',
            'description' => 'Sacando dinheiro',
        ]);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => 'Saque realizado com sucesso!',
                 ]);

        $this->assertDatabaseHas('accounts', [
            'number_account' => $this->account->number_account,
            'balance' => $this->account->balance - 1.00,
            'limit_credit' => $this->account->limit_credit,
            'created_at' => $this->datetime,
        ]);
    }

    public function test_withdraw_fails_with_invalid_amount()
    {
        $response = $this->postJson('/api/v1/withdraw', [
            'numberAccountOrigin' => $this->account->number_account,
            'value' => -1.00,
            'type' => 'withdraw',
            'description' => 'Sacando dinheiro',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'Saque não pode ser negativo!',
                 ]);
    }
}