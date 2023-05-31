<?php

namespace App\Domain\Financiamento;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;

interface CalculoParcelasServiceInterface
{
    public function calcularParcelas(Dinheiro $valorFinanciado, int $qtdParcelas, Taxa $taxaMensal): array;
}
