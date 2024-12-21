<?php

namespace App\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphChat;

class Handler extends WebhookHandler
{
    public function start()
    {
        $this->reply(message:'Добро пожаловать в помощник Тинькофф Инвестифции');
    }

    public function messageTo(){
        $chats = TelegraphChat::all();
        $query = json_decode($this->fetch());
        $RSI = $query->RSI;
        foreach ($chats as $chat){
            $chat->message('Сообщение '. now( ).' RSI = ' . $RSI)->send();
        }
    }

    public function fetch()
    {
        $queryParams = [
            'symbol' => 'RUS:SBER',
            'fields' => 'Recommend.Other,Recommend.All,Recommend.MA,RSI,RSI[1],Stoch.K,Stoch.D,Stoch.K[1],Stoch.D[1],CCI20,CCI20[1],ADX,ADX%2BDI,ADX-DI,ADX%2BDI[1],ADX-DI[1],AO,AO[1],AO[2],Mom,Mom[1],MACD.macd,MACD.signal,Rec.Stoch.RSI,Stoch.RSI.K,Rec.WR,W.R,Rec.BBPower,BBPower,Rec.UO,UO,EMA10,close,SMA10,EMA20,SMA20,EMA30,SMA30,EMA50,SMA50,EMA100,SMA100,EMA200,SMA200,Rec.Ichimoku,Ichimoku.BLine,Rec.VWMA,VWMA,Rec.HullMA9,HullMA9,Pivot.M.Classic.R3,Pivot.M.Classic.R2,Pivot.M.Classic.R1,Pivot.M.Classic.Middle,Pivot.M.Classic.S1,Pivot.M.Classic.S2,Pivot.M.Classic.S3,Pivot.M.Fibonacci.R3,Pivot.M.Fibonacci.R2,Pivot.M.Fibonacci.R1,Pivot.M.Fibonacci.Middle,Pivot.M.Fibonacci.S1,Pivot.M.Fibonacci.S2,Pivot.M.Fibonacci.S3,Pivot.M.Camarilla.R3,Pivot.M.Camarilla.R2,Pivot.M.Camarilla.R1,Pivot.M.Camarilla.Middle,Pivot.M.Camarilla.S1,Pivot.M.Camarilla.S2,Pivot.M.Camarilla.S3,Pivot.M.Woodie.R3,Pivot.M.Woodie.R2,Pivot.M.Woodie.R1,Pivot.M.Woodie.Middle,Pivot.M.Woodie.S1,Pivot.M.Woodie.S2,Pivot.M.Woodie.S3,Pivot.M.Demark.R1,Pivot.M.Demark.Middle,Pivot.M.Demark.S1',
            'no_404' => 'true',
            'label-product' => 'popup-technicals'
        ];

        $baseUrl = 'https://scanner.tradingview.com/symbol?';
        $url = $baseUrl . http_build_query($queryParams);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
        }

        curl_close($ch);

        return $response;
    }
}
