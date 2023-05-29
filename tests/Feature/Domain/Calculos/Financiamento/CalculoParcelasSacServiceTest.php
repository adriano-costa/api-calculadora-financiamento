<?php

namespace Test\Feature\Domain\Calculos\Financiamento;

use Decimal\Decimal;
use Tests\TestCase;

class CalculoParcelasSacServiceTest extends TestCase
{
    public function test_gerar_numero_prestacoes_sac(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));
        $this->assertEquals(5, count($parcelas));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($i + 1, $parcelas[$i]['numero']);
        }
    }

    public function test_calculo_valor_amortizacoes_sac(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('180.00', $parcelas[$i]['valorAmortizacao']);
        }
    }

    public function test_calculo_valor_juros_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));

        $this->assertEquals('16.11', $parcelas[0]['valorJuros']);
        $this->assertEquals('12.89', $parcelas[1]['valorJuros']);
        $this->assertEquals('9.67', $parcelas[2]['valorJuros']);
        $this->assertEquals('6.44', $parcelas[3]['valorJuros']);
        $this->assertEquals('3.22', $parcelas[4]['valorJuros']);
    }

    public function test_calculo_valor_prestacoes_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasSacService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 18), 5, new Decimal('0.0179', 9));

        $this->assertEquals('196.11', $parcelas[0]['valorPrestacao']);
        $this->assertEquals('192.89', $parcelas[1]['valorPrestacao']);
        $this->assertEquals('189.67', $parcelas[2]['valorPrestacao']);
        $this->assertEquals('186.44', $parcelas[3]['valorPrestacao']);
        $this->assertEquals('183.22', $parcelas[4]['valorPrestacao']);
    }
}
