<?php

namespace App\Casts;

use Brick\Math\BigDecimal as MathBigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BigDecimalCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $scale = isset($attributes[0]) ? $attributes[0] : 2;

        return MathBigDecimal::of($value, $scale);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $scale = isset($attributes[0]) ? $attributes[0] : 2;

        return $value->toScale($scale, RoundingMode::HALF_CEILING);
    }
}
