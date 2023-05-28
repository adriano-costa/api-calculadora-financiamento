<?php

namespace Test\Feature\Domain\Calculos\Financiamento;

use Decimal\Decimal;
use Tests\TestCase;

class CalculoParcelasPriceServiceTest extends TestCase
{
    public function test_gerar_numero_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 2), 5, new Decimal('0.0179', 9));
        $this->assertEquals(5, count($parcelas));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i + 1, $parcelas[$i]['numero']);
        }
    }

    public function test_calculo_valor_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 2), 5, new Decimal('0.0179', 9));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('189.78', $parcelas[$i]['valorPrestacao']->tostring());
        }
    }

    public function test_calculo_valor_juros_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 2), 5, new Decimal('0.0179', 9));

        $this->assertEquals('16.11', $parcelas[0]['valorJuros']->tostring());
        $this->assertEquals('13.00', $parcelas[1]['valorJuros']->tostring());
        $this->assertEquals('9.84', $parcelas[2]['valorJuros']->tostring());
        $this->assertEquals('6.62', $parcelas[3]['valorJuros']->tostring());
        $this->assertEquals('3.34', $parcelas[4]['valorJuros']->tostring());
    }

}
