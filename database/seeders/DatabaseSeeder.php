<?php

namespace Database\Seeders;

use Database\Factories\TransactionFactory;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        TransactionFactory::new()->count(10)->create();
    }
}
