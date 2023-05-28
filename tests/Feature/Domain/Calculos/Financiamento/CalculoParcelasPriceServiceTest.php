<?php

namespace Test\Feature\Domain\Calculos\Financiamento;

use Decimal\Decimal;
use Tests\TestCase;

class CalculoParcelasPriceServiceTest extends TestCase
{
    public function test_calculo_de_prestação_price(): void
    {
        $service = app()->make('App\Domain\Calculos\Financiamento\CalculoParcelasPriceService');
        $parcelas = $service->calcularParcelas(new Decimal('900', 2), 5, new Decimal('0.0179', 9));
        $this->assertEquals(5, count($parcelas));
        // dd($parcelas[0]['valorPrestacao']->tostring(), (new Decimal('173.67', 18))->tostring(), ($parcelas[0]['valorAmortizacao'])->tostring());
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals('189.78', $parcelas[$i]['valorPrestacao']->tostring());
        }
    }
}
