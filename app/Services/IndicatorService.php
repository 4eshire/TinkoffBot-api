<?php

namespace App\Services;

class IndicatorService
{

    private function ema(array $values, int $period): array
    {
        $ema = [];
        $k = 2 / ($period + 1);

        $ema[0] = $values[0];

        for ($i = 1; $i < count($values); $i++) {
            $ema[$i] = ($values[$i] - $ema[$i - 1]) * $k + $ema[$i - 1];
        }

        return $ema;
    }

    public function rsi(array $prices, int $period = 21): array
    {
        $rsi = [];
        $gains = [];
        $losses = [];

        for ($i = 1; $i < count($prices); $i++) {
            $change = $prices[$i] - $prices[$i - 1];
            $gains[]  = max($change, 0);
            $losses[] = max(-$change, 0);
        }

        if (count($gains) < $period) {
            return [];
        }

        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        $rs = $avgLoss == 0 ? 0 : $avgGain / $avgLoss;
        $rsi[$period] = 100 - (100 / (1 + $rs));

        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = (($avgGain * ($period - 1)) + $gains[$i]) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $losses[$i]) / $period;

            $rs = $avgLoss == 0 ? 0 : $avgGain / $avgLoss;
            $rsi[$i + 1] = 100 - (100 / (1 + $rs));
        }

        return $rsi;
    }

    public function macd(array $prices, int $fast = 8, int $slow = 17, int $signal = 9): array
    {
        $emaFast = $this->ema($prices, $fast);
        $emaSlow = $this->ema($prices, $slow);

        $macd = [];
        foreach ($prices as $i => $_) {
            $macd[$i] = $emaFast[$i] - $emaSlow[$i];
        }

        $signalLine = $this->ema(array_values($macd), $signal);


        return ['macd' => $macd, 'signal' => $signalLine];
    }
}
