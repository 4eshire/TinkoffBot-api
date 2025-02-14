<?php

namespace App\Telegram;

use App\Traits\ExternalRequests;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    use ExternalRequests;
    public function start()
    {
        $this->reply(message:'Добро пожаловать в помощник Тинькофф Инвестифции');
    }
}
