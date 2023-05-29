<?php

namespace App\Domain\Financiamento;

use Decimal\Decimal;

class CalculoParcelasSacService implements CalculoParcelasServiceInterface
{
    public function calcularParcelas(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal): array
    {
        $valorAmortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas);

        $parcelas = [];
        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $juros = $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $i);
            $prestacao = $this->calcularValorPrestacao($valorTotal, $qtdParcelas, $taxaMensal, $i);
            $parcelas[] = [
                'numero' => $i,
                'valorAmortizacao' => $this->formatarValor($valorAmortizacao),
                'valorJuros' => $this->formatarValor($juros),
                'valorPrestacao' => $this->formatarValor($prestacao),
            ];
        }

        return $parcelas;
    }

    /**
     * Calcula o valor da prestação em um sistema de amortização Sac
     */
    private function calcularValorPrestacao(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal, int $numeroParcela): Decimal
    {
        $valorPrestacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas)
            + $this->calcularJurosParcela($valorTotal, $qtdParcelas, $taxaMensal, $numeroParcela);

        return $valorPrestacao;
    }

    private function formatarValor(Decimal $valor, int $casasDecimais = 2): string
    {
        return $valor->round(2, Decimal::ROUND_HALF_EVEN)->toFixed($casasDecimais);
    }

    /**
     * Calcula o valor dos juros de uma parcela no sistema de amortização Sac
     */
    private function calcularJurosParcela(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal, int $numeroParcela): Decimal
    {
        throw_if(($numeroParcela < 1 || $numeroParcela > $qtdParcelas), new \InvalidArgumentException('Número da parcela inválido'));

        $amortizacao = $this->calcularValorAmortizacao($valorTotal, $qtdParcelas);
        $saldo = $valorTotal - ($numeroParcela - 1) * $amortizacao;
        $juros = $saldo * $taxaMensal;

        return $juros;
    }

    /**
     * Calcula o valor da amortização de uma parcela no sistema de amortização Sac
     */
    private function calcularValorAmortizacao(Decimal $valorTotal, int $qtdParcelas): Decimal
    {
        return $valorTotal / new Decimal($qtdParcelas, 18);
    }
}
