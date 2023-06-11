<?php

namespace App\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;

class MontaRespostaSimulacaoService
{
    public function __construct(private IdentificacaoProdutoService $identificador, private SimulacaoProdutoService $simulador)
    {
    }

    public function montarResposta(Dinheiro $valorFinanciado, int $prazo): array
    {
        $produto = $this->identificador->consultarProduto($valorFinanciado, $prazo);

        return [
            'codigoProduto' => $produto->CO_PRODUTO,
            'descricaoProduto' => $produto->NO_PRODUTO,
            'taxaJuros' => $produto->PC_TAXA_JUROS,
            'resultadoSimulacao' => $this->simulador->montarSimulacao($produto, $valorFinanciado, $prazo),
        ];
    }
}
