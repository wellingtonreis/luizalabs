<?php

namespace Database\Seeders;

use App\Models\Account;
use Database\Factories\TransactionFactory;
use Database\Factories\TransferFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Account::factory()->count(1000)->create();
        // TransactionFactory::new()->count(1000)->create();
        TransferFactory::new()->count(10000)->create();
    }
}
