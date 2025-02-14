<?php

namespace App\Console\Commands;

use App\Telegram\Handler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMessageCommand extends Command
{
    protected $signature = 'telegram:message';
    protected $description = '';

    public function handle()
    {
        Log::info('Отправка сигналов началась');
        $handler = new Handler();
        $handler->messageTo();
	Log::info('Отправка сигналов завершена');
    }
}
