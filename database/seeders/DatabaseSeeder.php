<?php

namespace Database\Seeders;

use App\Models\Account;
use Database\Factories\TransferFactory;
use Illuminate\Database\Seeder;
use App\Models\Transfer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Account::truncate();
        Transfer::truncate();
        
        Account::factory()->count(1000)->create();
        TransferFactory::new()->count(10000)->create();
    }
}
