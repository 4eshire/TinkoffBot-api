<?php

namespace App\Console\Commands;

use App\Models\Stocks;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;
use App\Services\{MoexService, IndicatorService};

class CalculateIndicators extends Command
{
    protected $signature = 'indicators:run';

    public function handle(
        MoexService $moex,
        IndicatorService $ind
    ) {
        $stocks = Stocks::all();
        $chats = TelegraphChat::all();
        $result = [];

        foreach ($stocks as $stock) {

            // ДНЕВНЫЕ ДАННЫЕ
            $daily = $moex->dailyCandles($stock->symbol);
            if (count($daily) < 60) continue;

            $dailyCloses = array_column($daily, 'close');
            $rsi = $ind->rsi($dailyCloses, 21);
            $lastDaily = count($dailyCloses) - 1;

            // НЕДЕЛЬНЫЕ ДАННЫЕ
            $weekly = $moex->aggregateWeekly($daily);
            if (count($weekly) < 30) continue;

            $weeklyCloses = array_column($weekly, 'close');
            [$macd, $signal] = $ind->macd($weeklyCloses);
            $lastWeekly = count($weeklyCloses) - 1;

            $row = [
                'ticker' => $stock->symbol,
                'week' => $weekly[$lastWeekly]['date'],
                'macd' => round($macd[$lastWeekly], 4),
                'signal' => round($signal[$lastWeekly], 4),
                'rsi21_daily' => array_key_exists($lastDaily, $rsi) ? round($rsi[$lastDaily], 2) : 'иди дебажить',
            ];

            foreach ($chats as $chat) {
                $chat->message("123"
//                    "📊 {$stock->symbol}\n".
//                    "MACD (Weekly): {$row['macd']}\n".
//                    "RSI(21) Daily: {$row['rsi21_daily']}"
                );
            }

            $result[] = $row;
        }

        $this->line(
            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
