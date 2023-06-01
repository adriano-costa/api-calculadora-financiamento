<?php

namespace App\Domain\Numeros;

class CastArrayComDecimaisService
{
    //metodo que itera sobre todos os elementos do array recursivamente e converte todos os tipos Taxa e Dinheiro para float
    public function tratarDecimaisParaFloat(array $valores): array
    {
        foreach ($valores as $chave => $valor) {
            if (is_array($valor)) {
                $valores[$chave] = $this->tratarDecimaisParaFloat($valor);
            }

            if ($valor instanceof Dinheiro) {
                $valores[$chave] = $valor->toFloat();
            }

            if ($valor instanceof Taxa) {
                $valores[$chave] = $valor->toFloat();
            }
        }

        return $valores;
    }
}
