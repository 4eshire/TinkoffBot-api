<?php

namespace App\Services;

class IndicatorService
{
    public function rsi(array $prices, int $period = 21): array
    {
        $count = count($prices);
        if ($count <= $period) return [];

        $rsi = [];
        $gains = [];
        $losses = [];

        for ($i = 1; $i < $count; $i++) {
            $change = $prices[$i] - $prices[$i - 1];
            $gains[] = max($change, 0);
            $losses[] = max(-$change, 0);
        }

        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        $rs = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
        $rsi[$period] = 100 - (100 / (1 + $rs));

        for ($i = $period; $i < count($gains); $i++) {
            $avgGain = (($avgGain * ($period - 1)) + $gains[$i]) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $losses[$i]) / $period;

            $rs = $avgLoss == 0 ? 100 : $avgGain / $avgLoss;
            $rsi[$i + 1] = 100 - (100 / (1 + $rs));
        }

        for ($i = 0; $i < $period; $i++) {
            if (!isset($rsi[$i])) $rsi[$i] = null;
        }

        ksort($rsi);
        return $rsi;
    }

    private function ema(array $values, int $period): array
    {
        $count = count($values);
        if ($count < $period) return [];

        $ema = [];
        $k = 2 / ($period + 1);

        $initialSma = array_sum(array_slice($values, 0, $period)) / $period;
        $ema[$period - 1] = $initialSma;

        for ($i = $period; $i < $count; $i++) {
            $ema[$i] = ($values[$i] - $ema[$i - 1]) * $k + $ema[$i - 1];
        }

        for ($i = 0; $i < $period - 1; $i++) {
            $ema[$i] = null;
        }

        ksort($ema);
        return $ema;
    }

    public function macd(array $prices, int $fast = 8, int $slow = 17, int $signal = 9): array
    {
        $emaFast = $this->ema($prices, $fast);
        $emaSlow = $this->ema($prices, $slow);

        $macd = [];

        foreach ($prices as $i => $_) {
            if (isset($emaFast[$i], $emaSlow[$i]) && $emaFast[$i] !== null && $emaSlow[$i] !== null) {
                $macd[$i] = $emaFast[$i] - $emaSlow[$i];
            } else {
                $macd[$i] = null;
            }
        }

        $filteredMacd = array_filter($macd, fn($v) => $v !== null);

        $signalLineRaw = $this->ema(array_values($filteredMacd), $signal);

        $signalLine = [];
        $sigIndex = 0;
        foreach ($macd as $i => $val) {
            if ($val === null) {
                $signalLine[$i] = null;
            } else {
                $signalLine[$i] = $signalLineRaw[$sigIndex] ?? null;
                $sigIndex++;
            }
        }

        ksort($macd);
        ksort($signalLine);

        return [
            'macd' => $macd,
            'signal' => $signalLine,
        ];
    }
}
