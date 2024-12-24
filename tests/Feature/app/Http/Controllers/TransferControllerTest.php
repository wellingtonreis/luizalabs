<?php

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use Faker\Factory;
use App\Models\Account;

class TransferControllerTest extends TestCase
{
    private $faker;
    private $datetime;
    private $accountOrigin;
    private $accountDestination;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();

        $accountNumberOrigin = $this->faker->randomNumber(8);
        $accountNumberDestination = $this->faker->randomNumber(8);

        $this->datetime = now()->format('Y-m-d H:i:s');
        Account::factory()->create(
            [
                'number_account' => $accountNumberOrigin,
                'balance' => $this->faker->randomFloat(2, 0, 10000),
                'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
                'created_at' => $this->datetime,
            ]
        );
        $this->accountOrigin = Account::where('number_account', $accountNumberOrigin)->first();

        Account::factory()->create(
            [
                'number_account' => $accountNumberDestination,
                'balance' => $this->faker->randomFloat(2, 0, 10000),
                'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
                'created_at' => $this->datetime,
            ]
        );
        $this->accountDestination = Account::where('number_account', $accountNumberDestination)->first();
    }

    public function test_transfer_successful()
    {
        $response = $this->postJson('/api/v1/transfer-funds', [
            'numberAccountOrigin' => $this->accountOrigin->number_account,
            'numberAccountDestination' => $this->accountDestination->number_account,
            'value' => 1.00,
            'type' => 'transfer',
            'description' => 'Transferindo dinheiro',
        ]);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => 'Transferência realizada com sucesso!',
                 ]);

        $this->assertDatabaseHas('accounts', [
            'number_account' => $this->accountOrigin->number_account,
            'balance' => $this->accountOrigin->balance - 1.00,
            'limit_credit' => $this->accountOrigin->limit_credit,
            'created_at' => $this->datetime,
        ]);

        $this->assertDatabaseHas('accounts', [
            'number_account' => $this->accountDestination->number_account,
            'balance' => $this->accountDestination->balance + 1.00,
            'limit_credit' => $this->accountDestination->limit_credit,
            'created_at' => $this->datetime,
        ]);
    }

    public function test_transfer_account_origin_equals_account_destination()
    {
        $response = $this->postJson('/api/v1/transfer-funds', [
            'numberAccountOrigin' => $this->accountOrigin->number_account,
            'numberAccountDestination' => $this->accountOrigin->number_account,
            'value' => 1.00,
            'type' => 'transfer',
            'description' => 'Transferindo dinheiro',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'Conta de origem e destino não podem ser iguais!',
                 ]);
    }

    public function test_transfer_insufficient_balance()
    {
        $response = $this->postJson('/api/v1/transfer-funds', [
            'numberAccountOrigin' => $this->accountOrigin->number_account,
            'numberAccountDestination' => $this->accountDestination->number_account,
            'value' => 100000.00,
            'type' => 'transfer',
            'description' => 'Transferindo dinheiro',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'Saldo insuficiênte',
                 ]);
    }

    public function test_transfer_insufficient_limit_credit()
    {
        $accountNumberOrigin = $this->faker->randomNumber(8);
        $accountNumberDestination = $this->faker->randomNumber(8);
        $datetime = now()->format('Y-m-d H:i:s');
        Account::factory()->create(
            [
                'number_account' => $accountNumberOrigin,
                'balance' => 100000 * -1,
                'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
                'created_at' => $datetime,
            ]
        );
        $accountOrigin = Account::where('number_account', $accountNumberOrigin)->first();

        Account::factory()->create(
            [
                'number_account' => $accountNumberDestination,
                'balance' => $this->faker->randomFloat(2, 0, 10000),
                'limit_credit' => $this->faker->randomFloat(2, 0, 10000),
                'created_at' => $datetime,
            ]
        );
        $accountDestination = Account::where('number_account', $accountNumberDestination)->first();

        $response = $this->postJson('/api/v1/transfer-funds', [
            'numberAccountOrigin' => $accountOrigin->number_account,
            'numberAccountDestination' => $accountDestination->number_account,
            'value' => 10000.00,
            'type' => 'transfer',
            'description' => 'Transferindo dinheiro',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'error' => 'Limite de crédito excedido',
                 ]);
    }
}