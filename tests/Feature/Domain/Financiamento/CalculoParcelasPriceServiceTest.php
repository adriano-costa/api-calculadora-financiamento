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
}
