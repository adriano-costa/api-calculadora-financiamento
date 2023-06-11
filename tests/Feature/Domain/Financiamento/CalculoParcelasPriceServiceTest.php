<?php

namespace Test\Feature\Domain\Financiamento;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use Tests\TestCase;

class CalculoParcelasPriceServiceTest extends TestCase
{
    public function test_gerar_numero_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));
        $this->assertEquals(5, count($parcelas));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i + 1, $parcelas[$i]['numero']);
        }
    }

    public function test_calculo_valor_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals(new Dinheiro('189.78'), $parcelas[$i]['valorPrestacao']);
        }
    }

    public function test_calculo_valor_juros_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));

        $this->assertEquals(new Dinheiro('16.11'), $parcelas[0]['valorJuros']);
        $this->assertEquals(new Dinheiro('13.00'), $parcelas[1]['valorJuros']);
        $this->assertEquals(new Dinheiro('9.84'), $parcelas[2]['valorJuros']);
        $this->assertEquals(new Dinheiro('6.62'), $parcelas[3]['valorJuros']);
        $this->assertEquals(new Dinheiro('3.34'), $parcelas[4]['valorJuros']);
    }

    public function test_calculo_valor_amortizazcoes_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));

        $this->assertEquals(new Dinheiro('173.67'), $parcelas[0]['valorAmortizacao']);
        $this->assertEquals(new Dinheiro('176.78'), $parcelas[1]['valorAmortizacao']);
        $this->assertEquals(new Dinheiro('179.94'), $parcelas[2]['valorAmortizacao']);
        $this->assertEquals(new Dinheiro('183.16'), $parcelas[3]['valorAmortizacao']);
        $this->assertEquals(new Dinheiro('186.44'), $parcelas[4]['valorAmortizacao']);
    }

    public function test_simulacao_grande(): void
    {
        $esperado = [
            [1, 15000, 4723.21, 19723.21],
            [2, 14929.15, 4794.06, 19723.21],
            [3, 14857.24, 4865.97, 19723.21],
            [4, 14784.25, 4938.96, 19723.21],
            [5, 14710.17, 5013.05, 19723.21],
            [6, 14634.97, 5088.24, 19723.21],
            [7, 14558.65, 5164.57, 19723.21],
            [8, 14481.18, 5242.04, 19723.21],
            [9, 14402.55, 5320.67, 19723.21],
            [10, 14322.74, 5400.48, 19723.21],
            [11, 14241.73, 5481.48, 19723.21],
            [12, 14159.51, 5563.7, 19723.21],
            [13, 14076.05, 5647.16, 19723.21],
            [14, 13991.35, 5731.87, 19723.21],
            [15, 13905.37, 5817.85, 19723.21],
            [16, 13818.1, 5905.11, 19723.21],
            [17, 13729.52, 5993.69, 19723.21],
            [18, 13639.62, 6083.6, 19723.21],
            [19, 13548.36, 6174.85, 19723.21],
            [20, 13455.74, 6267.47, 19723.21],
            [21, 13361.73, 6361.48, 19723.21],
            [22, 13266.31, 6456.91, 19723.21],
            [23, 13169.45, 6553.76, 19723.21],
            [24, 13071.15, 6652.07, 19723.21],
            [25, 12971.37, 6751.85, 19723.21],
            [26, 12870.09, 6853.13, 19723.21],
            [27, 12767.29, 6955.92, 19723.21],
            [28, 12662.95, 7060.26, 19723.21],
            [29, 12557.05, 7166.17, 19723.21],
            [30, 12449.56, 7273.66, 19723.21],
            [31, 12340.45, 7382.76, 19723.21],
            [32, 12229.71, 7493.5, 19723.21],
            [33, 12117.31, 7605.91, 19723.21],
            [34, 12003.22, 7720, 19723.21],
            [35, 11887.42, 7835.8, 19723.21],
            [36, 11769.88, 7953.33, 19723.21],
            [37, 11650.58, 8072.63, 19723.21],
            [38, 11529.49, 8193.72, 19723.21],
            [39, 11406.59, 8316.63, 19723.21],
            [40, 11281.84, 8441.38, 19723.21],
            [41, 11155.22, 8568, 19723.21],
            [42, 11026.7, 8696.52, 19723.21],
            [43, 10896.25, 8826.96, 19723.21],
            [44, 10763.84, 8959.37, 19723.21],
            [45, 10629.45, 9093.76, 19723.21],
            [46, 10493.05, 9230.17, 19723.21],
            [47, 10354.6, 9368.62, 19723.21],
            [48, 10214.07, 9509.15, 19723.21],
            [49, 10071.43, 9651.79, 19723.21],
            [50, 9926.65, 9796.56, 19723.21],
            [51, 9779.7, 9943.51, 19723.21],
            [52, 9630.55, 10092.66, 19723.21],
            [53, 9479.16, 10244.05, 19723.21],
            [54, 9325.5, 10397.71, 19723.21],
            [55, 9169.53, 10553.68, 19723.21],
            [56, 9011.23, 10711.98, 19723.21],
            [57, 8850.55, 10872.66, 19723.21],
            [58, 8687.46, 11035.75, 19723.21],
            [59, 8521.92, 11201.29, 19723.21],
            [60, 8353.9, 11369.31, 19723.21],
            [61, 8183.36, 11539.85, 19723.21],
            [62, 8010.27, 11712.95, 19723.21],
            [63, 7834.57, 11888.64, 19723.21],
            [64, 7656.24, 12066.97, 19723.21],
            [65, 7475.24, 12247.98, 19723.21],
            [66, 7291.52, 12431.7, 19723.21],
            [67, 7105.04, 12618.17, 19723.21],
            [68, 6915.77, 12807.44, 19723.21],
            [69, 6723.66, 12999.56, 19723.21],
            [70, 6528.67, 13194.55, 19723.21],
            [71, 6330.75, 13392.47, 19723.21],
            [72, 6129.86, 13593.35, 19723.21],
            [73, 5925.96, 13797.25, 19723.21],
            [74, 5719, 14004.21, 19723.21],
            [75, 5508.94, 14214.28, 19723.21],
            [76, 5295.72, 14427.49, 19723.21],
            [77, 5079.31, 14643.9, 19723.21],
            [78, 4859.65, 14863.56, 19723.21],
            [79, 4636.7, 15086.51, 19723.21],
            [80, 4410.4, 15312.81, 19723.21],
            [81, 4180.71, 15542.5, 19723.21],
            [82, 3947.57, 15775.64, 19723.21],
            [83, 3710.94, 16012.28, 19723.21],
            [84, 3470.75, 16252.46, 19723.21],
            [85, 3226.97, 16496.25, 19723.21],
            [86, 2979.52, 16743.69, 19723.21],
            [87, 2728.37, 16994.85, 19723.21],
            [88, 2473.44, 17249.77, 19723.21],
            [89, 2214.7, 17508.52, 19723.21],
            [90, 1952.07, 17771.14, 19723.21],
            [91, 1685.5, 18037.71, 19723.21],
            [92, 1414.94, 18308.28, 19723.21],
            [93, 1140.31, 18582.9, 19723.21],
            [94, 861.57, 18861.64, 19723.21],
            [95, 578.64, 19144.57, 19723.21],
            [96, 291.48, 19431.74, 19723.21],
        ];

        $service = app()->make('App\Domain\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Dinheiro('999999.99'), 96, new Taxa('0.015'));

        $this->assertEquals(count($parcelas), count($esperado));

        foreach ($parcelas as $parcela) {
            $this->assertEquals($parcela['numero'], $esperado[$parcela['numero'] - 1][0]);
            $this->assertEquals($parcela['valorJuros']->getValor()->toFloat(), $esperado[$parcela['numero'] - 1][1]);
            $this->assertEquals($parcela['valorAmortizacao']->getValor()->toFloat(), $esperado[$parcela['numero'] - 1][2]);
            $this->assertEquals($parcela['valorPrestacao']->getValor()->toFloat(), $esperado[$parcela['numero'] - 1][3]);
        }
    }
}
