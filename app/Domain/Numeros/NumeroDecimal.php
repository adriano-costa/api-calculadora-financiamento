<?php

namespace App\Domain\Numeros;

use Brick\Math\BigDecimal as MathBigDecimal;
use Brick\Math\BigRational;
use Brick\Math\RoundingMode;

class NumeroDecimal
{
    public const SCALE = 99;

    private MathBigDecimal $valor;

    public function __construct(float|string|MathBigDecimal|BigRational $valor)
    {
        if ($valor instanceof NumeroDecimal) {
            $this->valor = $valor->getValor()->toScale(static::SCALE, RoundingMode::HALF_CEILING);

            return;
        }

        if ($valor instanceof MathBigDecimal || $valor instanceof BigRational) {
            $this->valor = $valor->toScale(static::SCALE, RoundingMode::HALF_CEILING);

            return;
        }

        $this->valor = MathBigDecimal::of($valor)->toScale(static::SCALE, RoundingMode::HALF_CEILING);
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function toFloat()
    {
        return $this->valor->toFloat();
    }

    public function __toString()
    {
        return $this->valor->__toString();
    }
}
