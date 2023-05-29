<?php

namespace App\Domain\Produtos;

use App\Models\Produto;
use Decimal\Decimal;
use Illuminate\Database\Eloquent\Builder;

class IdentificacaoProdutoService
{
    public function consultarProduto(Decimal $valor, int $prazo): Produto
    {
        $produto = Produto::where('VR_MINIMO', '<=', $valor)
            ->where(function (Builder $query) use ($valor) {
                $query->where('VR_MAXIMO', '>=', $valor)
                    ->orWhereNull('VR_MAXIMO');
            })
            ->where('NU_MINIMO_MESES', '<=', $prazo)
            ->where(function (Builder $query) use ($prazo) {
                $query->where('NU_MAXIMO_MESES', '>=', $prazo)
                    ->orWhereNull('NU_MAXIMO_MESES');
            })
            ->first();

        return $produto;
    }
}
