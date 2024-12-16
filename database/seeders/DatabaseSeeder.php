<?php

namespace Database\Seeders;

use Database\Factories\AccountFactory;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        AccountFactory::new()->count(10)->create();
    }
}
