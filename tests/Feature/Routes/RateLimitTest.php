<?php

namespace Tests\Feature\Routes;

use App\Models\Produto;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('cache:clear');
        $this->artisan('db:seed');
        //mockar o serviço de notificação eventhub
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_api_rate_limit_esta_na_resposta()
    {
        $valorAtual = getenv('APP_RATE_LIMIT_PER_MINUTE');
        $response = $this->postJson('/v1/simulacao', ['valorDesejado' => 900, 'prazo' => 5]);

        $header_rate_limit = $response->headers->get('X-Ratelimit-Limit');
        $header_rate_remaining = $response->headers->get('X-RateLimit-Remaining');

        $this->assertEquals($valorAtual, $header_rate_limit);
        $this->assertEquals($valorAtual - 1, $header_rate_remaining);
    }

    public function test_rate_limit_decrementa_ate_bloquear()
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->postJson('/v1/simulacao', ['valorDesejado' => 900, 'prazo' => 5])
                ->assertOk()
                ->assertHeader('X-Ratelimit-Remaining', 10 - $i);
        }

        $this->postJson('/v1/simulacao', ['valorDesejado' => 900, 'prazo' => 5])
            ->assertStatus(429);
    }
}
