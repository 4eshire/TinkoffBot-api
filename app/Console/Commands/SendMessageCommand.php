<?php

namespace App\Console\Commands;

use App\Telegram\Handler;
use Illuminate\Console\Command;

class SendMessageCommand extends Command
{
    protected $signature = 'telegram:message';
    protected $description = 'Сообщение о покупке акции';

    public function handle()
    {
        $handler = new Handler;
            $handler->messageTo();
    }
}
