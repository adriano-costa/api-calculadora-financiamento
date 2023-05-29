<?php

namespace Test\Feature\Domain\Calculos\Financiamento;

use Decimal\Decimal;
use Tests\TestCase;

class CalculoParcelasPriceServiceTest extends TestCase
{
    public function test_gerar_numero_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));
        $this->assertEquals(5, count($parcelas));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i + 1, $parcelas[$i]['numero']);
        }
    }

    public function test_calculo_valor_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('189.78', $parcelas[$i]['valorPrestacao']);
        }
    }

    public function test_calculo_valor_juros_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));

        $this->assertEquals('16.11', $parcelas[0]['valorJuros']);
        $this->assertEquals('13.00', $parcelas[1]['valorJuros']);
        $this->assertEquals('9.84', $parcelas[2]['valorJuros']);
        $this->assertEquals('6.62', $parcelas[3]['valorJuros']);
        $this->assertEquals('3.34', $parcelas[4]['valorJuros']);
    }

    public function test_calculo_valor_amortizazcoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));

        $this->assertEquals('173.67', $parcelas[0]['valorAmortizacao']);
        $this->assertEquals('176.78', $parcelas[1]['valorAmortizacao']);
        $this->assertEquals('179.94', $parcelas[2]['valorAmortizacao']);
        $this->assertEquals('183.16', $parcelas[3]['valorAmortizacao']);
        $this->assertEquals('186.44', $parcelas[4]['valorAmortizacao']);
    }
}
