<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SimulacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valorDesejado' => 'required|numeric|min:0.01',
            'prazo' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {

        return [
            'valorDesejado.required' => 'O parametro valorDesejado é obrigatório',
            'valorDesejado.numeric' => 'O parametro valorDesejado deve ser um número',
            'valorDesejado.min' => 'O parametro valorDesejado deve ser maior ou igual a 0.01',
            'prazo.required' => 'O parametro prazo é obrigatório',
            'prazo.integer' => 'O parametro prazo deve ser um número inteiro',
            'prazo.min' => 'O parametro prazo deve ser maior ou igual a 1',
        ];

    }

    public function failedValidation(Validator $validator)
    {
        $envelopeErros = [
            'success' => false,
            'message' => 'Erro ao validar os parametros fornecidos',
            'data' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json(
            $envelopeErros,
            422,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        ));
    }
}
