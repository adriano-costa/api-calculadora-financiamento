<?php

namespace Database\Factories;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $valor = $this->faker->randomFloat(2, 0, 1000);

        return [
            'NO_PRODUTO' => $this->faker->name,
            'PC_TAXA_JUROS' => new Taxa($this->faker->randomFloat(4, 0, 1)),
            'NU_MINIMO_MESES' => $this->faker->randomNumber(1),
            'NU_MAXIMO_MESES' => $this->faker->randomNumber(3),
            'VR_MINIMO' => new Dinheiro($valor),
            'VR_MAXIMO' => new Dinheiro(100 * $valor),
        ];
    }
}
