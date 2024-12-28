<?php

namespace App\Traits;

trait ExternalRequests
{
    public function fetch($stock)
    {
        $queryParams = [
            'symbol' => 'RUS:' . $stock->symbol,
            'fields' => 'Recommend.Other|240,Recommend.All|240,Recommend.MA|240,RSI|240,RSI[1]|240,Stoch.K|240,Stoch.D|240,Stoch.K[1]|240,Stoch.D[1]|240,CCI20|240,CCI20[1]|240,ADX|240,ADX%2BDI|240,ADX-DI|240,ADX%2BDI[1]|240,ADX-DI[1]|240,AO|240,AO[1]|240,AO[2]|240,Mom|240,Mom[1]|240,MACD.macd|240,MACD.signal|240,Rec.Stoch.RSI|240,Stoch.RSI.K|240,Rec.WR|240,W.R|240,Rec.BBPower|240,BBPower|240,Rec.UO|240,UO|240,EMA10|240,close|240,SMA10|240,EMA20|240,SMA20|240,EMA30|240,SMA30|240,EMA50|240,SMA50|240,EMA100|240,SMA100|240,EMA200|240,SMA200|240,Rec.Ichimoku|240,Ichimoku.BLine|240,Rec.VWMA|240,VWMA|240,Rec.HullMA9|240,HullMA9|240,Pivot.M.Classic.R3|240,Pivot.M.Classic.R2|240,Pivot.M.Classic.R1|240,Pivot.M.Classic.Middle|240,Pivot.M.Classic.S1|240,Pivot.M.Classic.S2|240,Pivot.M.Classic.S3|240,Pivot.M.Fibonacci.R3|240,Pivot.M.Fibonacci.R2|240,Pivot.M.Fibonacci.R1|240,Pivot.M.Fibonacci.Middle|240,Pivot.M.Fibonacci.S1|240,Pivot.M.Fibonacci.S2|240,Pivot.M.Fibonacci.S3|240,Pivot.M.Camarilla.R3|240,Pivot.M.Camarilla.R2|240,Pivot.M.Camarilla.R1|240,Pivot.M.Camarilla.Middle|240,Pivot.M.Camarilla.S1|240,Pivot.M.Camarilla.S2|240,Pivot.M.Camarilla.S3|240,Pivot.M.Woodie.R3|240,Pivot.M.Woodie.R2|240,Pivot.M.Woodie.R1|240,Pivot.M.Woodie.Middle|240,Pivot.M.Woodie.S1|240,Pivot.M.Woodie.S2|240,Pivot.M.Woodie.S3|240,Pivot.M.Demark.R1|240,Pivot.M.Demark.Middle|240,Pivot.M.Demark.S1|240',
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
