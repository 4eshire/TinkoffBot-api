<?php

namespace Database\Seeders;

use Database\Seeders\Chats\ChatsTablesSeeder;
use Database\Seeders\Stocks\StocksTablesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ChatsTablesSeeder::class);
        $this->call(StocksTablesSeeder::class);
    }
}
