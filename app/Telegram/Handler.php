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
            $MACD_macd = $query->{'MACD.macd|240'};
            $MACD_signal = $query->{'MACD.signal|240'};
            if (($RSI >= 25 && $RSI <= 30) && ($MACD_macd >= $MACD_signal)) {
                foreach ($chats as $chat) {
                    $chat->html("<b>Сигнал на покупку</b>\n$stock->name\n$stock->symbol\nRSI: $RSI\nMACD (синяя): $MACD_macd\nMACD (оранжевая): $MACD_signal\nhttps://www.tbank.ru/invest/stocks/$stock->symbol?utm_source=security_share")->send();
                }
            }
            elseif (($RSI >= 65 && $RSI <= 80) && ($MACD_macd <= $MACD_signal)) {
                foreach ($chats as $chat) {
                    $chat->html("<b>Сигнал на продажу</b>\n$stock->name\n$stock->symbol\nRSI: $RSI\nMACD (синяя): $MACD_macd\nMACD (оранжевая): $MACD_signal\nhttps://www.tbank.ru/invest/stocks/$stock->symbol?utm_source=security_share")->send();
                }
            }
        }
    }
}
