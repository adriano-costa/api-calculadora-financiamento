<?php

namespace App\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Builder;

class IdentificacaoProdutoService
{
    public function consultarProduto(Dinheiro $valorFinanciado, int $prazo): Produto
    {
        $valor = $valorFinanciado->getValor();
        $produtos = Produto::where('VR_MINIMO', '<=', $valor)
            ->where(function (Builder $query) use ($valor) {
                $query->where('VR_MAXIMO', '>=', $valor)
                    ->orWhereNull('VR_MAXIMO');
            })
            ->where('NU_MINIMO_MESES', '<=', $prazo)
            ->where(function (Builder $query) use ($prazo) {
                $query->where('NU_MAXIMO_MESES', '>=', $prazo)
                    ->orWhereNull('NU_MAXIMO_MESES');
            })
            ->get();

        throw_if($produtos->isEmpty(), \InvalidArgumentException::class, 'Parametros incompatíveis com os produtos cadastrados.');
        throw_if($produtos->count() > 1, \DomainException::class, 'Os parametros de simulação apontam para mais de um produto. Essa situação não foi especificada.');

        return $produtos->first();
    }
}
