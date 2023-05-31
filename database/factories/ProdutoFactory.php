<?php

namespace Database\Factories;

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
            'PC_TAXA_JUROS' => $this->faker->randomFloat(4, 0, 1),
            'NU_MINIMO_MESES' => $this->faker->randomNumber(2),
            'NU_MAXIMO_MESES' => $this->faker->randomNumber(2),
            'VR_MINIMO' => $valor,
            'VR_MAXIMO' => 100 * $valor,
        ];
    }
}
