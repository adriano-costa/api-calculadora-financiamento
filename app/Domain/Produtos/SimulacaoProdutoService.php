<?php

namespace App\Domain\Produtos;

use App\Domain\Financiamento\CalculoParcelasPriceService;
use App\Domain\Financiamento\CalculoParcelasSacService;
use App\Domain\Financiamento\CalculoParcelasServiceInterface;
use App\Domain\Numeros\Dinheiro;
use App\Models\Produto;

class SimulacaoProdutoService
{
    private array $sistemasAmortizacao;

    public function __construct(CalculoParcelasPriceService $servicePrice, CalculoParcelasSacService $serviceSac)
    {
        $this->adicionarSistemaAmortizacao($serviceSac);
        $this->adicionarSistemaAmortizacao($servicePrice);
    }

    public function adicionarSistemaAmortizacao(CalculoParcelasServiceInterface $sistemaAmortizacao)
    {
        $this->sistemasAmortizacao[] = $sistemaAmortizacao;
    }

    public function montarSimulacao(Produto $produto, Dinheiro $valorFinanciado, int $prazo): array
    {
        if ($this->sistemasAmortizacao == null || count($this->sistemasAmortizacao) == 0) {
            throw new \InvalidArgumentException('Nenhum sistema de amortização foi adicionado.');
        }

        $resultadoSimulacao = [];
        foreach ($this->sistemasAmortizacao as $sistema) {
            $resultadoSimulacao[] = [
                'tipo' => $sistema->getNomeSitema(),
                'parcelas' => $sistema->calcularParcelas($valorFinanciado, $prazo, $produto->PC_TAXA_JUROS),
            ];
        }

        return $resultadoSimulacao;
    }
}
