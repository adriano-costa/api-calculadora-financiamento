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
                'valorAmortizacao' => $this->calcularValorAmortizacao($valorTotal, $qtdParcelas, $taxaMensal, $i),
                'valorJuros' => $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $i),
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
        return ((1 + $taxaMensal) ** $qtdParcelas)
            / (((1 + $taxaMensal) ** $qtdParcelas) - 1);
    }

    private function arrendondarValor(Decimal $valor, int $casasDecimais = 2): Decimal
    {
        return $valor->round(2, Decimal::ROUND_HALF_EVEN);
    }

    /**
     * Calcula o valor dos juros de uma parcela no sistema de amortização Price
     */
    private function calcularJurosParcela(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal, int $numeroParcela): Decimal
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $valorParcela = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $saldoDevedor = $this->calcularSaldoDevedor($valorTotal, $taxaMensal, $numeroParcela, $valorParcela);
        $jurosParcela = $this->calcularJuros($saldoDevedor, $taxaMensal);

        return $this->arrendondarValor($jurosParcela);
    }

    private function calcularSaldoDevedor(Decimal $valorTotal, Decimal $taxaMensal, int $numeroParcela, Decimal $valorParcela): Decimal
    {

        return $valorTotal * ((1 + $taxaMensal) ** ($numeroParcela - 1))
            - $valorParcela * (((1 + $taxaMensal) ** ($numeroParcela - 1)) - 1) / $taxaMensal;

    }

    private function calcularJuros(Decimal $saldoDevedor, Decimal $taxaMensal): Decimal
    {
        return $saldoDevedor * $taxaMensal;
    }

    /**
     * Calcula o valor da amortização de uma parcela no sistema de amortização Price
     */
    private function calcularValorAmortizacao(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal, int $numeroParcela): Decimal
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $valorPrestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $valorAmortizacao = $valorPrestacao - $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $numeroParcela);

        return $this->arrendondarValor($valorAmortizacao);
    }
}
