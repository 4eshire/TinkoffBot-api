<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stock;
use App\Services\{MoexService, IndicatorService};
use DefStudio\Telegraph\Models\TelegraphChat;

class CalculateIndicators extends Command
{
    protected $signature = 'indicators:run';
    protected $description = 'Calculate RSI and MACD indicators including current day';

    public function handle(MoexService $moex, IndicatorService $ind)
    {
        $from = now()->subYears(7)->toDateString();
        $till = now()->toDateString();

        foreach (Stock::all() as $stock) {
            $this->info("=== {$stock->symbol} ===");

            $daily = $moex->daily($stock->symbol, $from, $till);

            // ** НЕ УБИРАЕМ неполный текущий день, используем все данные **
            if (count($daily) < 100) {
                $this->warn("Недостаточно данных для {$stock->symbol}");
                continue;
            }

            $dailyClose = array_map(fn($c) => (float)$c['close'], $daily);

            $weekly = $moex->aggregateWeekly($daily);
            $weeklyClose = array_map(fn($c) => (float)$c['close'], $weekly);

            $rsiValues = $ind->rsi($dailyClose, 21);
            $rsiLast = round(end($rsiValues), 2);

            $macd = $ind->macd($weeklyClose, 8, 17, 9);

            $macdLast = null;
            $signalLast = null;
            foreach (array_reverse($macd['macd'], true) as $i => $v) {
                if ($v !== null) {
                    $macdLast = $v;
                    $signalLast = $macd['signal'][$i] ?? null;
                    break;
                }
            }

            $macdLast = $macdLast !== null ? round($macdLast, 2) : null;
            $signalLast = $signalLast !== null ? round($signalLast, 2) : null;

            $minRsi = $stock->minRsi ?? 30;
            $maxRsi = $stock->maxRsi ?? 70;

            $this->line("RSI(21) (1D) last: {$rsiLast}");
            $this->line("MACD (W) last: {$macdLast}");
            $this->line("Signal last: {$signalLast}");

            foreach (TelegraphChat::all() as $chat) {
                if (($macdLast < 0 && $signalLast < 0 && $macdLast >= $signalLast) && ($rsiLast <= $minRsi)) {
                    $chat->message(
                        "**Сигнал на открытие лонг позиции**".
                        "📊 {$stock->symbol}\n".
                        "{$stock->name}\n".
                        "RSI(21) (1D): {$rsiLast}\n".
                        "🟦 MACD (W): {$macdLast}\n".
                        "🟧 Signal: {$signalLast}\n".
                        "https://www.tbank.ru/invest/stocks/{$stock->symbol}?utm_source=security_share"
                    )->send();
                }
                elseif (($macdLast > 0 && $signalLast > 0 && $macdLast <= $signalLast) && ($rsiLast >= $maxRsi)) {
                    $chat->message(
                        "**Сигнал на открытие шорт позиции**".
                        "📊 {$stock->symbol}\n".
                        "{$stock->name}\n".
                        "RSI(21) (1D): {$rsiLast}\n".
                        "🟦 MACD (W): {$macdLast}\n".
                        "🟧 Signal: {$signalLast}\n".
                        "https://www.tbank.ru/invest/stocks/{$stock->symbol}?utm_source=security_share"
                    )->send();
                }
            }
        }

        return self::SUCCESS;
    }
}
