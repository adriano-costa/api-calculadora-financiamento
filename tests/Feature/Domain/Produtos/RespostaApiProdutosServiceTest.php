<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Produtos\RespostaApiProdutosService;
use Tests\TestCase;

class RespostaApiProdutosServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
    }

    public function test_monta_resposta_parametro_valido()
    {
        //validar a seguinte resposta:
        //     {
        //         "codigoProduto": 1,
        //         "descricaoProduto": "Produto 1",
        //         "taxaJuros": 0.0179,
        //         "resultadoSimulacao": [
        //              {
        //                   "tipo": "SAC",
        //                   "parcelas": [
        //                        {
        //                             "numero": 1,
        //                             "valorAmortizacao": 180.00,
        //                             "valorJuros": 16.11,
        //                             "valorPrestacao": 196.11
        //                        },
        //                        {
        //                             "numero": 2,
        //                             "valorAmortizacao": 180.00,
        //                             "valorJuros": 12.89,
        //                             "valorPrestacao": 192.89
        //                        },
        //                        {
        //                             "numero": 3,
        //                             "valorAmortizacao": 180.00,
        //                             "valorJuros": 9.67,
        //                             "valorPrestacao": 189.67
        //                        },
        //                        {
        //                             "numero": 4,
        //                             "valorAmortizacao": 180.00,
        //                             "valorJuros": 6.44,
        //                             "valorPrestacao": 186.44
        //                        },
        //                        {
        //                             "numero": 5,
        //                             "valorAmortizacao": 180.00,
        //                             "valorJuros": 3.22,
        //                             "valorPrestacao": 183.22
        //                        }
        //                   ]
        //              },
        //              {
        //                   "tipo": "PRICE",
        //                   "parcelas": [
        //                        {
        //                             "numero": 1,
        //                             "valorAmortizacao": 173.67,
        //                             "valorJuros": 16.11,
        //                             "valorPrestacao": 189.78
        //                        },
        //                        {
        //                             "numero": 2,
        //                             "valorAmortizacao": 176.78,
        //                             "valorJuros": 13.00,
        //                             "valorPrestacao": 189.78
        //                        },
        //                        {
        //                             "numero": 3,
        //                             "valorAmortizacao": 179.94,
        //                             "valorJuros": 9.84,
        //                             "valorPrestacao": 189.78
        //                        },
        //                        {
        //                             "numero": 4,
        //                             "valorAmortizacao": 183.16,
        //                             "valorJuros": 6.62,
        //                             "valorPrestacao": 189.78
        //                        },
        //                        {
        //                             "numero": 5,
        //                             "valorAmortizacao": 186.44,
        //                             "valorJuros": 3.34,
        //                             "valorPrestacao": 189.78
        //                        }
        //                   ]
        //              }
        //         ]
        //    }

        $service = app()->make(RespostaApiProdutosService::class);

    }
}
