<?php

namespace App\Domain\Calculos\Financiamento;

use Decimal\Decimal;

interface CalculoParcelasServiceInterface
{
    public function calcularParcelas(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal): array;
}
