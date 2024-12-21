<?php

namespace Database\Seeders;

use Database\Factories\TransactionFactory;
use Database\Factories\TransferFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        TransactionFactory::new()->count(10)->create();
        TransferFactory::new()->count(100)->create();
    }
}
