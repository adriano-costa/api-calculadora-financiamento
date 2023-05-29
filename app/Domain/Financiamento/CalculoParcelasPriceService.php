<?php

namespace App\Domain\Financiamento;

use Decimal\Decimal;

class CalculoParcelasPriceService implements CalculoParcelasServiceInterface
{
    public function calcularParcelas(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal): array
    {
        $valorPrestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $parcelas = [];
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $amortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas, $taxaMensal, $i);
            $juros = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $i);
            $parcelas[] = [
                'numero' => $i,
                'valorAmortizacao' => $this->formatarValor($amortizacao),
                'valorJuros' => $this->formatarValor($juros),
                'valorPrestacao' => $this->formatarValor($valorPrestacao),
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

        return $valorPrestacao;
    }

    private function calculaFatorPrice(Decimal $taxaMensal, int $qtdParcelas): Decimal
    {
        return ((1 + $taxaMensal) ** $qtdParcelas)
            / (((1 + $taxaMensal) ** $qtdParcelas) - 1);
    }

    private function formatarValor(Decimal $valor, int $casasDecimais = 2): string
    {
        return $valor->round(2, Decimal::ROUND_HALF_EVEN)->toFixed($casasDecimais);
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

        return $jurosParcela;
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

        return $valorAmortizacao;
    }
}
