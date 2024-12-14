<?php

namespace App\Telegram;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{
    public function start()
    {
        $this->reply(message:'Добро пожаловать в помошник Тинькоф Инвестифции');
    }

    public function messageTo(){
        $chats = TelegraphChat::all();
        foreach ($chats as $chat){
            $chat->message('Сообщение '. now())->send();
        }

    }
}
