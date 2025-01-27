<?php

namespace Database\Seeders\Stocks;

use Illuminate\Database\Seeder;

class StocksTablesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(StocksTableSeeder::class);
    }
}
