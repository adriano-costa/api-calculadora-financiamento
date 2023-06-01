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
    }
}
