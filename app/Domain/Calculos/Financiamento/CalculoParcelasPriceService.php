<?php

namespace App\Domain\Calculos\Financiamento;

use Decimal\Decimal;

class CalculoParcelasPriceService implements CalculoParcelasServiceInterface
{
    public function calcularParcelas(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal): array
    {
        $valorPrestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $parcelas = [];
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $parcelas[] = [
                'numero' => $i,
                'valorPrestacao' => $valorPrestacao,
            ];
        }

        return $parcelas;
    }

    /**
     * Calcula o valor da prestação em um sistema de amortização Price
     */
    private function calcularValorPrestacao(Decimal $valor, int $qtdParcelas, Decimal $taxaMensal): Decimal
    {
        $fator = $this->calculaFatorPrice($taxaMensal, $qtdParcelas);
        $valorPrestacao = $valor * $taxaMensal * $fator;

        return $this->arrendondarValor($valorPrestacao);
    }

    private function calculaFatorPrice(Decimal $taxaMensal, int $qtdParcelas): Decimal
    {
        return ((1 + $taxaMensal) ** $qtdParcelas) / (((1 + $taxaMensal) ** $qtdParcelas) - 1);
    }

    public function arrendondarValor(Decimal $valor, int $casasDecimais = 2): Decimal
    {
        return $valor->round(2, Decimal::ROUND_HALF_EVEN);
    }
}
