<?php

namespace App\Casts;

use App\Domain\Numeros\Dinheiro as NumerosDinheiro;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DinheiroCast implements CastsAttributes
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

        return new NumerosDinheiro($value);
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

        return $value->getValor();
    }
}
