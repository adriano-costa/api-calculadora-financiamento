<?php

namespace Database\Seeders;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use App\Models\Produto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = [
            [
                'CO_PRODUTO' => 1,
                'NO_PRODUTO' => 'Produto 1',
                'PC_TAXA_JUROS' => new Taxa(0.017900000),
                'NU_MINIMO_MESES' => 0,
                'NU_MAXIMO_MESES' => 24,
                'VR_MINIMO' => new Dinheiro(200.00),
                'VR_MAXIMO' => new Dinheiro(10000.00),
            ],
            [
                'CO_PRODUTO' => 2,
                'NO_PRODUTO' => 'Produto 2',
                'PC_TAXA_JUROS' => new Taxa(0.017500000),
                'NU_MINIMO_MESES' => 25,
                'NU_MAXIMO_MESES' => 48,
                'VR_MINIMO' => new Dinheiro(10001.00),
                'VR_MAXIMO' => new Dinheiro(100000.00),
            ],
            [
                'CO_PRODUTO' => 3,
                'NO_PRODUTO' => 'Produto 3',
                'PC_TAXA_JUROS' => new Taxa(0.018200000),
                'NU_MINIMO_MESES' => 49,
                'NU_MAXIMO_MESES' => 96,
                'VR_MINIMO' => new Dinheiro(100000.01),
                'VR_MAXIMO' => new Dinheiro(1000000.00),
            ],
            [
                'CO_PRODUTO' => 4,
                'NO_PRODUTO' => 'Produto 4',
                'PC_TAXA_JUROS' => new Taxa(0.015100000),
                'NU_MINIMO_MESES' => 96,
                'NU_MAXIMO_MESES' => null,
                'VR_MINIMO' => new Dinheiro(1000000.01),
                'VR_MAXIMO' => null,
            ],
        ];

        if (config('database.default') == 'sqlsrv') {
            DB::unprepared('SET IDENTITY_INSERT produto ON');
        }
        foreach ($produtos as $produto) {
            Produto::create($produto);
        }
        if (config('database.default') == 'sqlsrv') {
            DB::unprepared('SET IDENTITY_INSERT produto OFF');
        }
    }
}
