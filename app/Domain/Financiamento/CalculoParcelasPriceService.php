<?php

namespace App\Domain\Financiamento;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use Brick\Math\BigRational;

class CalculoParcelasPriceService implements CalculoParcelasServiceInterface
{
    public function calcularParcelas(Dinheiro $valorFinanciado, int $qtdParcelas, Taxa $taxaMensal): array
    {
        $valorTotal = $valorFinanciado->getValor()->toBigRational();
        $taxa = $taxaMensal->getValor()->toBigRational();

        $valorPrestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxa);
        $parcelas = [];
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $amortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas, $taxa, $i);
            $juros = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxa, $i);
            $parcelas[] = [
                'numero' => $i,
                'valorAmortizacao' => new Dinheiro($amortizacao),
                'valorJuros' => new Dinheiro($juros),
                'valorPrestacao' => new Dinheiro($valorPrestacao),
            ];
        }

        return $parcelas;
    }

    /**
     * Calcula o valor da prestação em um sistema de amortização Price
     */
    private function calcularValorPrestacao(BigRational $valor, int $qtdParcelas, BigRational $taxaMensal): BigRational
    {
        $fator = $this->calculaFatorPrice($taxaMensal, $qtdParcelas);
        $valorPrestacao = $valor->multipliedBy($taxaMensal)->multipliedBy($fator);

        return $valorPrestacao;
    }

    private function calculaFatorPrice(BigRational $taxaMensal, int $qtdParcelas): BigRational
    {
        return $taxaMensal->plus(1)->power($qtdParcelas)->dividedBy(
            $taxaMensal->plus(1)->power($qtdParcelas)->minus(1)
        );
    }

    /**
     * Calcula o valor dos juros de uma parcela no sistema de amortização Price
     */
    private function calcularJurosParcela(BigRational $valorTotal, int $qtdParcelas, BigRational $taxaMensal, int $numeroParcela): BigRational
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $valorParcela = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $saldoDevedor = $this->calcularSaldoDevedor($valorTotal, $taxaMensal, $numeroParcela, $valorParcela);
        $jurosParcela = $this->calcularJuros($saldoDevedor, $taxaMensal);

        return $jurosParcela;
    }

    private function calcularSaldoDevedor(BigRational $valorTotal, BigRational $taxaMensal, int $numeroParcela, BigRational $valorParcela): BigRational
    {
        return $valorTotal->multipliedBy(
            $taxaMensal->plus(1)->power($numeroParcela - 1)
        )->minus(
            $valorParcela->multipliedBy(
                $taxaMensal->plus(1)->power($numeroParcela - 1)->minus(1)->dividedBy($taxaMensal)
            )
        );
    }

    private function calcularJuros(BigRational $saldoDevedor, BigRational $taxaMensal): BigRational
    {
        return $saldoDevedor->multipliedBy($taxaMensal);
    }

    /**
     * Calcula o valor da amortização de uma parcela no sistema de amortização Price
     */
    private function calcularValorAmortizacao(BigRational $valorTotal, int $qtdParcelas, BigRational $taxaMensal, int $numeroParcela): BigRational
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $valorPrestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal);
        $jurosParcela = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $numeroParcela);
        $valorAmortizacao = $valorPrestacao->minus($jurosParcela);

        return $valorAmortizacao;
    }
}
