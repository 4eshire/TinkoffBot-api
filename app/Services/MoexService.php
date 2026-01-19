<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoexService
{
    public function dailyCandles(string $symbol): array
    {
        $response = Http::get(
            "https://iss.moex.com/iss/engines/stock/markets/shares/securities/{$symbol}/candles.json",
            [
                'interval' => 24,
                'from' => now()->subYears(2)->toDateString(),
                'till' => now()->toDateString(),
            ]
        )->json();

        $data = [];
        foreach ($response['candles']['data'] ?? [] as $row) {
            $data[] = [
                'date'  => substr($row[0], 0, 10),
                'open'  => (float)$row[1],
                'high'  => (float)$row[2],
                'low'   => (float)$row[3],
                'close' => (float)$row[4],
            ];
        }

        return $data;
    }

    public function aggregateWeekly(array $daily): array
    {
        $weeks = [];

        foreach ($daily as $candle) {
            $key = date('o-W', strtotime($candle['date']));

            if (!isset($weeks[$key])) {
                $weeks[$key] = $candle;
            } else {
                $weeks[$key]['high']  = max($weeks[$key]['high'], $candle['high']);
                $weeks[$key]['low']   = min($weeks[$key]['low'], $candle['low']);
                $weeks[$key]['close'] = $candle['close'];
            }
        }

        return array_values($weeks);
    }
}
