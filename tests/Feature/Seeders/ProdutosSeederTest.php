<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;

class ProdutosSeederTest extends TestCase
{
    public function test_seeder_de_produtos(): void
    {
        $this->artisan('db:seed', ['--class' => 'ProdutosSeeder']);
        $this->assertDatabaseCount('produtos', 4);
    }
}
