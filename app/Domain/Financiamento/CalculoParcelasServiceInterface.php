<?php

namespace App\Domain\Financiamento;

use Decimal\Decimal;

interface CalculoParcelasServiceInterface
{
    public function calcularParcelas(Decimal $valorTotal, int $qtdParcelas, Decimal $taxaMensal): array;
}
