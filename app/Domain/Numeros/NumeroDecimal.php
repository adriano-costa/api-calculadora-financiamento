<?php

namespace App\Domain\Numeros;

use Brick\Math\BigDecimal as MathBigDecimal;
use Brick\Math\RoundingMode;

class NumeroDecimal
{
    public const SCALE = 99;

    private MathBigDecimal $valor;

    public function __construct(float|string|MathBigDecimal $valor)
    {
        if ($valor instanceof NumeroDecimal) {
            $this->valor = $valor->getValor()->toScale(static::SCALE, RoundingMode::HALF_CEILING);

            return;
        }

        if ($valor instanceof MathBigDecimal) {
            $this->valor = $valor->toScale(static::SCALE, RoundingMode::HALF_CEILING);

            return;
        }

        $this->valor = MathBigDecimal::of($valor, static::SCALE);
    }

    public function getValor()
    {
        return $this->valor;
    }
}
