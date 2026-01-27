<?php

namespace App\Console\Commands;

use App\Models\Stocks;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Console\Command;
use App\Services\{MoexService, IndicatorService};

class CalculateIndicators extends Command
{
    protected $signature = 'indicators:run';
    protected $description = '';

    public function handle(MoexService $moex, IndicatorService $ind)
    {
        $chats = TelegraphChat::all();

        $stocks = Stocks::all();

        $from = now()->subYears(6)->toDateString();
        $till = now()->toDateString();

        foreach ($stocks as $stock) {
            $this->info("=== {$stock->symbol} ===");

            $daily = $moex->daily($stock->symbol, $from, $till);
            $dailyClose = array_map(fn($c) => (float)$c['close'], $daily);
            $rsi = $ind->rsi($dailyClose);
            $rsiLast = $rsi[array_key_last($rsi)];

            $weekly = $moex->weekly($stock->symbol, $from, $till);
            $weeklyClose = array_map(fn($c) => (float)$c['close'], $weekly);
            $macd = $ind->macd($weeklyClose);

            $this->line('RSI(21) last: ' . round($rsiLast, 2));
            $this->line('MACD last: ' . round(end($macd['macd']), 2));
            $this->line('Signal last: ' . round(end($macd['signal']), 2));
        }

        foreach ($chats as $chat) {
                $chat->message(
                    "📊 {$stock->symbol}\n".
                    "{$stock->name}\n".
                    "RSI(21) last: ".round($rsiLast, 2)." (=== Считается не коррктно ===)\n".
                    "🟦MACD last: ".round(end($macd['macd']), 2)."\n".
                    "🟧Signal last: ".round(end($macd['signal']), 2)."\n".
                    "https://www.tbank.ru/invest/stocks/{$stock->symbol}?utm_source=security_share"
                )->send();
            }

        return self::SUCCESS;
    }



}

//    public function handle(MoexService $moex, IndicatorService $indicators) {
//        $stocks = Stocks::all();
//        $from = now()->subYears(5)->toDateString();
//        $till = now()->toDateString();
//        $chats = TelegraphChat::all();
//        $result = [];
//
//        foreach ($stocks as $stock) {
//            $this->info("=== {$stock->symbol} ===");
//
//            $daily = $moex->getDailyCandles($stock->symbol, $from, $till);
//            $dailyCloses = array_column($daily, 4);
//
//            $weekly = $moex->aggregateWeekly($daily);
//            $weeklyCloses = array_column($weekly, 'close');
//
//            $rsi = $indicators->rsi($dailyCloses, 21);
//            $macd = $indicators->macd($weeklyCloses);
//
//            $this->line("RSI(21) last: " . end($rsi));
//            $this->line("MACD last: " . end($macd['macd']));
//            $this->line("Signal last: " . end($macd['signal']));
//            $this->newLine();
//        }
//
////            foreach ($chats as $chat) {
////                $chat->message(
////                    "📊 {$stock->symbol}\n".
////                    "MACD (Weekly): {$row['macd']}\n".
////                    "RSI(21) Daily: {$row['rsi21_daily']}"
////                )->send();
////            }
//
////            $result[] = $row;
//        }
//
////        $this->line(
////            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
////        );
//}
