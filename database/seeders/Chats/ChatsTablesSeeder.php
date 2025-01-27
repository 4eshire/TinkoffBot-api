<?php

namespace Database\Seeders\Chats;

use Illuminate\Database\Seeder;

class ChatsTablesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ChatsTableSeeder::class);
    }
}
