<?php

namespace App\Domain\Produtos;

use App\Models\Produto;
use Decimal\Decimal;

class IdentificacaoProdutoService
{
    public function consultarProduto(Decimal $valor, int $prazo): Produto
    {
        $produto = Produto::where('VR_MINIMO', '<=', $valor)
            ->where('VR_MAXIMO', '>=', $valor)
            ->where('NU_MINIMO_MESES', '<=', $prazo)
            ->where('NU_MAXIMO_MESES', '>=', $prazo)
            ->first();

        return $produto;
    }
}
