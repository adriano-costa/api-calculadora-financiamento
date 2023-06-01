<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Numeros\Taxa;
use App\Models\Produto;
use Tests\TestCase;

class MontaRespostaSimulacaoServiceTest extends TestCase
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

    public function test_resposta_simulacao_eh_montada()
    {
        $service = app()->make(\App\Domain\Produtos\MontaRespostaSimulacaoService::class);
        $valor = new \App\Domain\Numeros\Dinheiro('900.00');
        $prazo = 5;

        $resposta = $service->montarResposta($valor, $prazo);

        $this->assertIsArray($resposta);
        $this->assertCount(4, $resposta);
        $this->assertArrayHasKey('codigoProduto', $resposta);
        $this->assertArrayHasKey('descricaoProduto', $resposta);
        $this->assertArrayHasKey('taxaJuros', $resposta);
        $this->assertArrayHasKey('resultadoSimulacao', $resposta);
        $this->assertEquals(1, $resposta['codigoProduto']);
        $this->assertEquals('Produto 1', $resposta['descricaoProduto']);
        $this->assertEquals(new Taxa(0.0179), $resposta['taxaJuros']);
        $this->assertIsArray($resposta['resultadoSimulacao']);
    }
}
