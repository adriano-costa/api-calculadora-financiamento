<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\IdentificacaoProdutoService;
use App\Domain\Produtos\SimulacaoProdutoService;
use App\Models\Produto;
use Tests\TestCase;

class SimulacaoProdutoServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_monta_resposta_com_os_resultados_da_simulacao()
    {
        $serviceConsulta = app()->make(IdentificacaoProdutoService::class);
        $valorFinanciado = new Dinheiro(900);
        $prazo = 5;
        $produto = $serviceConsulta->consultarProduto($valorFinanciado, $prazo);

        $service = app()->make(SimulacaoProdutoService::class);

        $resultadoSimulacao = $service->montarSimulacao($produto, $valorFinanciado, $prazo);
        $this->assertIsArray($resultadoSimulacao);
        $this->assertCount(2, $resultadoSimulacao);
        $this->assertEquals('SAC', $resultadoSimulacao[0]['tipo']);
        $this->assertEquals('PRICE', $resultadoSimulacao[1]['tipo']);
        $this->assertIsArray($resultadoSimulacao[0]['parcelas']);
        $this->assertCount(5, $resultadoSimulacao[0]['parcelas']);
        $this->assertIsArray($resultadoSimulacao[1]['parcelas']);
        $this->assertCount(5, $resultadoSimulacao[1]['parcelas']);

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
    }
}
