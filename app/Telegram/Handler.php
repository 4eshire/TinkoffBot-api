<?php

namespace App\Telegram;

use App\Models\Stocks;
use App\Traits\ExternalRequests;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{
    use ExternalRequests;
    public function start()
    {
        $this->reply(message:'Добро пожаловать в помощник Тинькофф Инвестифции');
    }

    public function messageTo(){
        $chats = TelegraphChat::all();
        $stocks = Stocks::all();
        foreach ($stocks as $stock){
            $query = json_decode($this->fetch($stock));
            $RSI = $query->{'RSI|240'};
            foreach ($chats as $chat){
                $chat->message('Сообщение '. now( ).' RSI ' . $stock->name .' = ' . $RSI)->send();
            }
        }
    }
}
