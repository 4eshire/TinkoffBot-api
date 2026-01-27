<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoexService
{
    private function candles(string $ticker, int $interval, string $from, string $till): array
    {
        $url = "https://iss.moex.com/iss/engines/stock/markets/shares/securities/{$ticker}/candles.json";

        $json = Http::get($url, [
            'interval' => $interval,
            'from'     => $from,
            'till'     => $till,
        ])->json();

        $columns = $json['candles']['columns'] ?? [];
        $data    = $json['candles']['data'] ?? [];

        return array_values(array_filter(
            array_map(fn ($row) => array_combine($columns, $row), $data),
            fn ($c) => isset($c['close']) && $c['close'] > 0
        ));
    }

    public function daily(string $ticker, string $from, string $till): array
    {
        return $this->candles($ticker, 24, $from, $till);
    }

    public function weekly(string $ticker, string $from, string $till): array
    {
        return $this->candles($ticker, 7, $from, $till);
    }
}
