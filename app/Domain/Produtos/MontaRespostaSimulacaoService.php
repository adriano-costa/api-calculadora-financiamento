<?php

namespace App\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;

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

    //metodo que itera sobre todos os elementos do array recursivamente e converte todos os tipos Taxa e Dinheiro para float
    private function tratarDecimaisParaFloat(array $valores): array
    {
        foreach ($valores as $chave => $valor) {
            if (is_array($valor)) {
                $valores[$chave] = $this->tratarDecimaisParaFloat($valor);
            }

            if ($valor instanceof Dinheiro) {
                $valores[$chave] = $valor->toFloat();
            }

            if ($valor instanceof Taxa) {
                $valores[$chave] = $valor->toFloat();
            }
        }

        return $valores;
    }
}
