<?php

namespace Test\Feature\Domain\Produtos;

use App\Domain\Produtos\IdentificacaoProdutoService;
use Decimal\Decimal;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IdentificacaoProdutoServiceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
    }

    public function test_consegue_consultar_produto(): void
    {
        $service = app()->make(IdentificacaoProdutoService::class);
        $valor = new Decimal('900.00', 18);
        $prazo = 5;
        $produto = $service->consultarProduto($valor, $prazo);
        $this->assertEquals('Produto 1', $produto->NO_PRODUTO);
    }

}
