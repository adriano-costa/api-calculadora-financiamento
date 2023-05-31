<?php

namespace Tests\Feature\Models;

use App\Models\Produto;
use Decimal\Decimal;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Produto::truncate();
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_consegue_consultar_produto(): void
    {
        Produto::factory()->create();
        $produto = Produto::first();
        $this->assertNotNull($produto);
    }

    public function test_produto_possui_todas_as_colunas_com_seus_valores()
    {
        Produto::factory()->create([
            'CO_PRODUTO' => 1,
            'NO_PRODUTO' => 'Produto 1',
            'PC_TAXA_JUROS' => 0.123456789,
            'NU_MINIMO_MESES' => 1,
            'NU_MAXIMO_MESES' => 12,
            'VR_MINIMO' => 123456789.13,
            'VR_MAXIMO' => 123000789.17,
        ]);

        $produto = Produto::find(1);
        $this->assertEquals(1, $produto->CO_PRODUTO);
        $this->assertEquals('Produto 1', $produto->NO_PRODUTO);
        $this->assertEquals(new Decimal('0.123456789', 10), $produto->PC_TAXA_JUROS);
        $this->assertEquals(1, $produto->NU_MINIMO_MESES);
        $this->assertEquals(12, $produto->NU_MAXIMO_MESES);
        $this->assertEquals(new Decimal('123456789.13', 18), $produto->VR_MINIMO);
        $this->assertEquals(new Decimal('123000789.17', 18), $produto->VR_MAXIMO);
    }

    public function test_colunas_decimal_mantem_precisao_do_valor()
    {
        Produto::factory()->create([
            'VR_MINIMO' => 123456789.13,
            'VR_MAXIMO' => 123000789.17,
            'PC_TAXA_JUROS' => 0.123456789,
        ]);

        $produto = Produto::first();
        $this->assertEquals(new Decimal('123456789.13', 18), $produto->VR_MINIMO);
        $this->assertEquals(new Decimal('123000789.17', 18), $produto->VR_MAXIMO);
        $this->assertEquals(new Decimal('0.123456789', 10), $produto->PC_TAXA_JUROS);
    }
}
