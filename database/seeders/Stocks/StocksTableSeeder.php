<?php

namespace Database\Seeders\Stocks;

use App\Models\Stock;
use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    public array $stocks = [
        'SOFL' => ['name' => 'Софтлайн', 'min_rsi' => 30, 'max_rsi' => 65],
        'VSEH' => ['name' => 'ВИ.ру', 'min_rsi' => 20, 'max_rsi' => 70],
        'CNRU' => ['name' => 'МКПАО "Циан"', 'min_rsi' => 35, 'max_rsi' => 70],
        'LSRG' => ['name' => 'Группа ЛСР ПАО', 'min_rsi' => 30, 'max_rsi' => 60],
        'AQUA' => ['name' => 'ПАО ИНАРКТИКА', 'min_rsi' => 27, 'max_rsi' => 65],
        'TRMK' => ['name' => 'Трубная Металлургическая Компания', 'min_rsi' => 30, 'max_rsi' => 72],
        'PRMD' => ['name' => 'ПРОМОМЕД', 'min_rsi' => 25, 'max_rsi' => 60],
        'BELU' => ['name' => 'НоваБев Групп', 'min_rsi' => 20, 'max_rsi' => 70],
        'FLOT' => ['name' => 'Совкомфлот', 'min_rsi' => 35, 'max_rsi' => 60],
        'ETLN' => ['name' => 'МКПАО Эталон Груп', 'min_rsi' => 30, 'max_rsi' => 60],
        'SGZH' => ['name' => 'Сегежа', 'min_rsi' => 25, 'max_rsi' => 65],
        'UWGN' => ['name' => 'ПАО НПК ОВК', 'min_rsi' => 30, 'max_rsi' => 70],
        'RENI' => ['name' => 'Ренессанс Страхование', 'min_rsi' => 32, 'max_rsi' => 70],
        'MTLR' => ['name' => 'Мечел ПАО', 'min_rsi' => 28, 'max_rsi' => 65],
        'SVCB' => ['name' => 'Совкомбанк', 'min_rsi' => 25, 'max_rsi' => 70],
        'ALRS' => ['name' => 'АЛРОСА ПАО', 'min_rsi' => 30, 'max_rsi' => 65],
        'ASTR' => ['name' => 'Группа Астра', 'min_rsi' => 30, 'max_rsi' => 60],
        'RAGR' => ['name' => 'Группа Русагро', 'min_rsi' => 30, 'max_rsi' => 70],
        'DATA' => ['name' => 'Группа Аренадата', 'min_rsi' => 25, 'max_rsi' => 70],
        'IVAT' => ['name' => 'ПАО ИВА', 'min_rsi' => 25, 'max_rsi' => 65],
        'PIKK' => ['name' => 'ПИК СЗ (ПАО)', 'min_rsi' => 25, 'max_rsi' => 65],
        'SNGSP' => ['name' => 'Сургутнефтегаз ПАО - прив.', 'min_rsi' => 30, 'max_rsi' => 63],
        'MOEX' => ['name' => 'ПАО Московская биржа', 'min_rsi' => 27, 'max_rsi' => 60],
        'VKCO' => ['name' => 'Междулародная компания ПАО ВК', 'min_rsi' => 30, 'max_rsi' => 70],
        'MAGN' => ['name' => '"Магнитогорский Металургический Комбинат" ПАО', 'min_rsi' => 25, 'max_rsi' => 70],
        'NLMK' => ['name' => 'ПАО "НЛМК"', 'min_rsi' => 30, 'max_rsi' => 65],
        'WUSH' => ['name' => 'ВУШ Холдинг', 'min_rsi' => 27, 'max_rsi' => 60],
        'GMKN' => ['name' => 'ГМК "Норильский Никель" ПАО', 'min_rsi' => 35, 'max_rsi' => 65],
        'RUAL' => ['name' => 'РУСАЛ ОК МКПАО', 'min_rsi' => 40, 'max_rsi' => 70],
        'VTBR' => ['name' => 'ПАО Банк ВТБ', 'min_rsi' => 27, 'max_rsi' => 63],
        'SELG' => ['name' => 'ПАО "Селигдар"', 'min_rsi' => 33, 'max_rsi' => 67],
        'AFLT' => ['name' => 'Аэрофлот-росс. авиалин (ПАО)ао', 'min_rsi' => 33, 'max_rsi' => 65],
        'ROSN' => ['name' => 'ПАО НК Роснефть', 'min_rsi' => 35, 'max_rsi' => 60],
        'IRAO' => ['name' => '"Интер РАО" ПАО', 'min_rsi' => 30, 'max_rsi' => 65]
    ];
    public function run() {
        collect($this->stocks)->each(function($data, $symbol) {
            $stock = Stock::firstOrCreate(['symbol' => $symbol, 'name' => $data['name']]);
            $stock->update(collect($data)->all());
        });
    }
}
