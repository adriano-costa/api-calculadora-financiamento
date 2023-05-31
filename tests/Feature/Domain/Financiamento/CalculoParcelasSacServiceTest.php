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
}
