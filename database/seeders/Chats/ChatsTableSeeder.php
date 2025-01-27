<?php

namespace Database\Seeders\Chats;

use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Database\Seeder;

class ChatsTableSeeder extends Seeder
{
    public array $chats = [
        'TinkoffBotApi' => ['token' => '7208417693:AAH5I3sYrZ1WV994R2YsLomkqpgdaceY4vk1'],
    ];
    public function run() {
        collect($this->chats)->each(function($data, $name) {
            $chat = TelegraphBot::firstOrCreate(['name' => $name, 'token' => $data['token']]);
            $chat->update(collect($data)->all());
        });
    }
}
