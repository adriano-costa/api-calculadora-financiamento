<?php

namespace App\Domain\Financiamento;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use Brick\Math\BigRational;

class CalculoParcelasSacService implements CalculoParcelasServiceInterface
{
    public function calcularParcelas(Dinheiro $valorFinanciado, int $qtdParcelas, Taxa $taxaMensal): array
    {
        $valorTotal = $valorFinanciado->getValor()->toBigRational();
        $taxa = $taxaMensal->getValor()->toBigRational();

        $valorAmortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas);

        $parcelas = [];
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $juros = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxa, $i);
            $prestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxa, $i);
            $parcelas[] = [
                'numero' => $i,
                'valorAmortizacao' => new Dinheiro($valorAmortizacao),
                'valorJuros' => new Dinheiro($juros),
                'valorPrestacao' => new Dinheiro($prestacao),
            ];
        }

        return $parcelas;
    }

    /**
     * Calcula o valor da prestação em um sistema de amortização Sac
     */
    private function calcularValorPrestacao(BigRational $valorTotal, int $qtdParcelas, BigRational $taxaMensal, int $numeroParcela): BigRational
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $amortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas);
        $juros = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $numeroParcela);

        return $amortizacao->plus($juros);
    }

    /**
     * Calcula o valor dos juros de uma parcela no sistema de amortização Sac
     */
    private function calcularJurosParcela(BigRational $valorTotal, int $qtdParcelas, BigRational $taxaMensal, int $numeroParcela): BigRational
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $amortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas);
        $saldo = $valorTotal->minus($amortizacao->multipliedBy($numeroParcela - 1));
        $juros = $saldo->multipliedBy($taxaMensal);

        return $juros;
    }

    /**
     * Calcula o valor da amortização de uma parcela no sistema de amortização Sac
     */
    private function calcularValorAmortizacao(BigRational $valorTotal, int $qtdParcelas): BigRational
    {
        return $valorTotal->dividedBy($qtdParcelas);
    }
}
