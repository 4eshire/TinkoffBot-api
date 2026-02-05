<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoexService
{
    private string $baseUrl = 'https://iss.moex.com/iss/engines/stock/markets/shares/securities';

    public function daily(string $ticker, string $from, string $till): array
    {
        return $this->candles($ticker, 24, $from, $till);
    }

    private function candles(string $ticker, int $interval, string $from, string $till): array
    {
        $result = [];
        $start = 0;

        do {
            $json = Http::get("{$this->baseUrl}/{$ticker}/candles.json", [
                'interval' => $interval,
                'from'     => $from,
                'till'     => $till,
                'start'    => $start,
            ])->json();

            $columns = $json['candles']['columns'] ?? [];
            $data    = $json['candles']['data'] ?? [];

            if (empty($data)) {
                break;
            }

            foreach ($data as $row) {
                $result[] = array_combine($columns, $row);
            }

            $start += count($data);
        } while (true);

        usort($result, fn($a, $b) => strtotime($a['begin']) <=> strtotime($b['begin']));

        return array_values(array_filter($result, fn($c) => isset($c['close']) && $c['close'] > 0));
    }

    public function aggregateWeekly(array $daily): array
    {
        $weeks = [];

        foreach ($daily as $candle) {
            $weekKey = date('o-W', strtotime($candle['begin']));

            if (!isset($weeks[$weekKey])) {
                $weeks[$weekKey] = [
                    'open'  => $candle['open'],
                    'high'  => $candle['high'],
                    'low'   => $candle['low'],
                    'close' => $candle['close'],
                ];
            } else {
                $weeks[$weekKey]['high']  = max($weeks[$weekKey]['high'], $candle['high']);
                $weeks[$weekKey]['low']   = min($weeks[$weekKey]['low'], $candle['low']);
                $weeks[$weekKey]['close'] = $candle['close'];
            }
        }

        return array_values($weeks);
    }
}
