<?php

namespace Tests\Feature\Seeders;

use App\Models\Produto;
use Tests\TestCase;

class ProdutosSeederTest extends TestCase
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

    public function test_seeder_de_produtos(): void
    {
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
        $this->assertDatabaseCount('produto', 4);
    }
}
