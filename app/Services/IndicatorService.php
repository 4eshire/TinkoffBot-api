<?php

namespace App\Services;

class IndicatorService
{
    private function ema(array $data, int $period): array
    {
        $k = 2 / ($period + 1);
        $ema[0] = $data[0];

        for ($i = 1; $i < count($data); $i++) {
            $ema[$i] = $data[$i] * $k + $ema[$i - 1] * (1 - $k);
        }

        return $ema;
    }

    // MACD (8,17,9) — недельный
    public function macd(array $closes): array
    {
        $ema8  = $this->ema($closes, 8);
        $ema17 = $this->ema($closes, 17);

        $macd = array_map(
            fn($v, $i) => $v - $ema17[$i],
            $ema8,
            array_keys($ema8)
        );

        $signal = $this->ema($macd, 9);

        $hist = array_map(
            fn($v, $i) => $v - $signal[$i],
            $macd,
            array_keys($macd)
        );

        return [$macd, $signal];
    }

    // RSI (21) — дневной
    public function rsi(array $closes, int $period = 21): array
    {
        $gains = $losses = [];

        for ($i = 1; $i < count($closes); $i++) {
            $diff = $closes[$i] - $closes[$i - 1];
            $gains[] = max($diff, 0);
            $losses[] = max(-$diff, 0);
        }

        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        $rsi[$period] = $avgLoss == 0
            ? 100
            : 100 - (100 / (1 + $avgGain / $avgLoss));

        for ($i = $period + 1; $i < count($gains); $i++) {
            $avgGain = ($avgGain * ($period - 1) + $gains[$i]) / $period;
            $avgLoss = ($avgLoss * ($period - 1) + $losses[$i]) / $period;

            $rsi[$i] = $avgLoss == 0
                ? 100
                : 100 - (100 / (1 + $avgGain / $avgLoss));
        }

        return $rsi;
    }
}
