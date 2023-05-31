<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\IdentificacaoProdutoService;
use App\Models\Produto;
use Tests\TestCase;

class IdentificacaoProdutoServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Produto::truncate();
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_consegue_consultar_produto(): void
    {
        $service = app()->make(IdentificacaoProdutoService::class);
        $valor = new Dinheiro('900.00');
        $prazo = 5;
        $produto = $service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 1', $produto->NO_PRODUTO);
    }

    public function test_consegue_consultar_produto_com_valor_maximo_null(): void
    {
        $service = app()->make(IdentificacaoProdutoService::class);
        $valor = new Dinheiro('2000000.01');
        $prazo = 96;
        $produto = $service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 4', $produto->NO_PRODUTO);
    }

    public function test_consegue_consultar_produto_com_prazo_maximo_null(): void
    {
        $service = app()->make(IdentificacaoProdutoService::class);
        $valor = new Dinheiro('1000000.01');
        $prazo = 120;
        $produto = $service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 4', $produto->NO_PRODUTO);
    }

    public function test_lanca_excecao_com_valor_prazo_incompativel()
    {
        $service = app()->make(IdentificacaoProdutoService::class);
        $valor = new Dinheiro('1000.00');
        $prazo = 96;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parametros incompatíveis com os produtos cadastrados.');
        $service->consultarProduto($valor, $prazo);
    }
}
