<?php

namespace Database\Seeders\Stocks;

use App\Models\Stocks;
use Illuminate\Database\Seeder;

class StocksTableSeeder extends Seeder
{
    public array $stocks = [
        'SBER' => ['name' => 'Сбер Банк'],
        'POSI' => ['name' => 'Группа Позитив'],
        'TRNFP' => ['name' => 'Транснефть'],
        'LKOH' => ['name' => 'Лукойл'],
        'NVTK' => ['name' => 'НОВАТЭК'],
        'SVCB' => ['name' => 'Совкомбанк'],
        'YDEX' => ['name' => 'Яндекс'],
        'ROSN' => ['name' => 'Роснефть'],
        'SMLT' => ['name' => 'ГК Самолет'],
        'CHMF' => ['name' => 'Северсталь'],
        'MAGN' => ['name' => 'ММК'],
        'TRMK' => ['name' => 'ТМК'],
        'T' => ['name' => 'Т-Технологии'],
        'BELU' => ['name' => 'Novabev Group'],
        'WUSH' => ['name' => 'Whoosh'],
        'GMKN' => ['name' => 'Норильский никель'],
        'OZON' => ['name' => 'Ozon Holdings PLC'],
        'MGNT' => ['name' => 'Магнит'],
        'VTBR' => ['name' => 'Банк ВТБ']
    ];
    public function run() {
        collect($this->stocks)->each(function($data, $symbol) {
            $stock = Stocks::firstOrCreate(['symbol' => $symbol, 'name' => $data['name']]);
            $stock->update(collect($data)->all());
        });
    }
}
