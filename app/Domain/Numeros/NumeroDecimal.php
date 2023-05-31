<?php

namespace App\Domain\Numeros;

use Brick\Math\BigDecimal as MathBigDecimal;

class NumeroDecimal
{
    public const SCALE = 99;

    private MathBigDecimal $valor;

    public function __construct(float|string|MathBigDecimal $valor)
    {
        if (! $valor instanceof BigDecimal) {
            $this->valor = MathBigDecimal::of($valor, self::SCALE);

            return;
        }

        $this->valor = $valor->toScale(self::SCALE);
    }

    public function getValor()
    {
        return $this->valor;
    }
}
