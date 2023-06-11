<?php

namespace Test\Feature\Domain\Financiamento;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use Tests\TestCase;

class CalculoParcelasSacServiceTest extends TestCase
{
    public function test_gerar_numero_prestacoes_sac(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));
        $this->assertEquals(5, count($parcelas));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i + 1, $parcelas[$i]['numero']);
        }
    }

    public function test_calculo_valor_amortizacoes_sac(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals(new Dinheiro('180.00'), $parcelas[$i]['valorAmortizacao']);
        }
    }

    public function test_calculo_valor_juros_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));

        $this->assertEquals(new Dinheiro('16.11'), $parcelas[0]['valorJuros']);
        $this->assertEquals(new Dinheiro('12.89'), $parcelas[1]['valorJuros']);
        $this->assertEquals(new Dinheiro('9.67'), $parcelas[2]['valorJuros']);
        $this->assertEquals(new Dinheiro('6.44'), $parcelas[3]['valorJuros']);
        $this->assertEquals(new Dinheiro('3.22'), $parcelas[4]['valorJuros']);
    }

    public function test_calculo_valor_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Dinheiro('900'), 5, new Taxa('0.0179'));

        $this->assertEquals(new Dinheiro('196.11'), $parcelas[0]['valorPrestacao']);
        $this->assertEquals(new Dinheiro('192.89'), $parcelas[1]['valorPrestacao']);
        $this->assertEquals(new Dinheiro('189.67'), $parcelas[2]['valorPrestacao']);
        $this->assertEquals(new Dinheiro('186.44'), $parcelas[3]['valorPrestacao']);
        $this->assertEquals(new Dinheiro('183.22'), $parcelas[4]['valorPrestacao']);
    }

    public function test_simulacao_grande(): void
    {
        $esperado = [
            [1, 15000, 10416.67, 25416.67],
            [2, 14843.75, 10416.67, 25260.42],
            [3, 14687.5, 10416.67, 25104.17],
            [4, 14531.25, 10416.67, 24947.92],
            [5, 14375, 10416.67, 24791.67],
            [6, 14218.75, 10416.67, 24635.42],
            [7, 14062.5, 10416.67, 24479.17],
            [8, 13906.25, 10416.67, 24322.92],
            [9, 13750, 10416.67, 24166.67],
            [10, 13593.75, 10416.67, 24010.42],
            [11, 13437.5, 10416.67, 23854.17],
            [12, 13281.25, 10416.67, 23697.92],
            [13, 13125, 10416.67, 23541.67],
            [14, 12968.75, 10416.67, 23385.42],
            [15, 12812.5, 10416.67, 23229.17],
            [16, 12656.25, 10416.67, 23072.92],
            [17, 12500, 10416.67, 22916.67],
            [18, 12343.75, 10416.67, 22760.42],
            [19, 12187.5, 10416.67, 22604.17],
            [20, 12031.25, 10416.67, 22447.92],
            [21, 11875, 10416.67, 22291.67],
            [22, 11718.75, 10416.67, 22135.42],
            [23, 11562.5, 10416.67, 21979.17],
            [24, 11406.25, 10416.67, 21822.92],
            [25, 11250, 10416.67, 21666.67],
            [26, 11093.75, 10416.67, 21510.42],
            [27, 10937.5, 10416.67, 21354.17],
            [28, 10781.25, 10416.67, 21197.92],
            [29, 10625, 10416.67, 21041.67],
            [30, 10468.75, 10416.67, 20885.42],
            [31, 10312.5, 10416.67, 20729.17],
            [32, 10156.25, 10416.67, 20572.92],
            [33, 10000, 10416.67, 20416.67],
            [34, 9843.75, 10416.67, 20260.42],
            [35, 9687.5, 10416.67, 20104.17],
            [36, 9531.25, 10416.67, 19947.92],
            [37, 9375, 10416.67, 19791.67],
            [38, 9218.75, 10416.67, 19635.42],
            [39, 9062.5, 10416.67, 19479.17],
            [40, 8906.25, 10416.67, 19322.92],
            [41, 8750, 10416.67, 19166.67],
            [42, 8593.75, 10416.67, 19010.42],
            [43, 8437.5, 10416.67, 18854.17],
            [44, 8281.25, 10416.67, 18697.92],
            [45, 8125, 10416.67, 18541.67],
            [46, 7968.75, 10416.67, 18385.42],
            [47, 7812.5, 10416.67, 18229.17],
            [48, 7656.25, 10416.67, 18072.92],
            [49, 7500, 10416.67, 17916.67],
            [50, 7343.75, 10416.67, 17760.42],
            [51, 7187.5, 10416.67, 17604.17],
            [52, 7031.25, 10416.67, 17447.92],
            [53, 6875, 10416.67, 17291.67],
            [54, 6718.75, 10416.67, 17135.42],
            [55, 6562.5, 10416.67, 16979.17],
            [56, 6406.25, 10416.67, 16822.92],
            [57, 6250, 10416.67, 16666.67],
            [58, 6093.75, 10416.67, 16510.42],
            [59, 5937.5, 10416.67, 16354.17],
            [60, 5781.25, 10416.67, 16197.92],
            [61, 5625, 10416.67, 16041.67],
            [62, 5468.75, 10416.67, 15885.42],
            [63, 5312.5, 10416.67, 15729.17],
            [64, 5156.25, 10416.67, 15572.92],
            [65, 5000, 10416.67, 15416.67],
            [66, 4843.75, 10416.67, 15260.42],
            [67, 4687.5, 10416.67, 15104.17],
            [68, 4531.25, 10416.67, 14947.92],
            [69, 4375, 10416.67, 14791.67],
            [70, 4218.75, 10416.67, 14635.42],
            [71, 4062.5, 10416.67, 14479.17],
            [72, 3906.25, 10416.67, 14322.92],
            [73, 3750, 10416.67, 14166.67],
            [74, 3593.75, 10416.67, 14010.42],
            [75, 3437.5, 10416.67, 13854.17],
            [76, 3281.25, 10416.67, 13697.92],
            [77, 3125, 10416.67, 13541.67],
            [78, 2968.75, 10416.67, 13385.42],
            [79, 2812.5, 10416.67, 13229.17],
            [80, 2656.25, 10416.67, 13072.92],
            [81, 2500, 10416.67, 12916.67],
            [82, 2343.75, 10416.67, 12760.42],
            [83, 2187.5, 10416.67, 12604.17],
            [84, 2031.25, 10416.67, 12447.92],
            [85, 1875, 10416.67, 12291.67],
            [86, 1718.75, 10416.67, 12135.42],
            [87, 1562.5, 10416.67, 11979.17],
            [88, 1406.25, 10416.67, 11822.92],
            [89, 1250, 10416.67, 11666.67],
            [90, 1093.75, 10416.67, 11510.42],
            [91, 937.5, 10416.67, 11354.17],
            [92, 781.25, 10416.67, 11197.92],
            [93, 625, 10416.67, 11041.67],
            [94, 468.75, 10416.67, 10885.42],
            [95, 312.5, 10416.67, 10729.17],
            [96, 156.25, 10416.67, 10572.92],
        ];

        $service = app()->make('App\Domain\Financiamento\CalculoParcelasSacService');
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
