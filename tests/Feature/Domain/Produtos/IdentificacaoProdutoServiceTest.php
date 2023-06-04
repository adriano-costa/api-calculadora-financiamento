<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Numeros\Taxa;
use App\Domain\Produtos\IdentificacaoProdutoService;
use App\Models\Produto;
use Tests\TestCase;

class IdentificacaoProdutoServiceTest extends TestCase
{
    private IdentificacaoProdutoService $service;

    public function setUp(): void
    {
        parent::setUp();
        Produto::truncate();
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
        $this->service = app()->make(IdentificacaoProdutoService::class);
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_consegue_consultar_produto(): void
    {
        $valor = new Dinheiro('900.00');
        $prazo = 5;
        $produto = $this->service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 1', $produto->NO_PRODUTO);
    }

    public function test_consegue_consultar_produto_com_valor_maximo_null(): void
    {
        $valor = new Dinheiro('2000000.01');
        $prazo = 96;
        $produto = $this->service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 4', $produto->NO_PRODUTO);
    }

    public function test_consegue_consultar_produto_com_prazo_maximo_null(): void
    {
        $valor = new Dinheiro('1000000.01');
        $prazo = 120;
        $produto = $this->service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 4', $produto->NO_PRODUTO);
    }

    public function test_lanca_excecao_com_valor_prazo_incompativel()
    {
        $valor = new Dinheiro('1000.00');
        $prazo = 96;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parametros incompatíveis com os produtos cadastrados.');
        $this->service->consultarProduto($valor, $prazo);
    }

    public function test_lanca_excecao_se_localizar_produtos_com_parametros_coincidentes()
    {
        Produto::create([
            'CO_PRODUTO' => 1,
            'NO_PRODUTO' => 'Produto 1 similar',
            'PC_TAXA_JUROS' => new Taxa(0.0200000),
            'NU_MINIMO_MESES' => 12,
            'NU_MAXIMO_MESES' => 24,
            'VR_MINIMO' => new Dinheiro(1000.00),
            'VR_MAXIMO' => new Dinheiro(10000.00),
        ]);
        $valor = new Dinheiro('2000.00');
        $prazo = 20;
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Os parametros de simulação apontam para mais de um produto. Essa situação não foi especificada.');

        $this->service->consultarProduto($valor, $prazo);
    }
}
