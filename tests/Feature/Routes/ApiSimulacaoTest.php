<?php

namespace Tests\Feature\Routes;

use App\Models\Produto;
use Tests\TestCase;

class ApiSimulacaoTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        //mockar o serviço de notificação eventhub
    }

    public function tearDown(): void
    {
        Produto::truncate();
        parent::tearDown();
    }

    public function test_aplicacao_retorna_resposta_bem_sucussedida(): void
    {
        $this->mock(\App\Domain\EventHub\NotificarEventHubService::class, function ($mock) {
            $mock->shouldReceive('notificar')->once();
        });

        $response = $this->postJson('/', ['valorDesejado' => 900, 'prazo' => 5]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'codigoProduto' => 1,
                'descricaoProduto' => 'Produto 1',
                'taxaJuros' => 0.0179,
                'resultadoSimulacao' => [
                    [
                        'tipo' => 'SAC',
                        'parcelas' => [
                            [
                                'numero' => 1,
                                'valorAmortizacao' => 180.00,
                                'valorJuros' => 16.11,
                                'valorPrestacao' => 196.11,
                            ],
                            [
                                'numero' => 2,
                                'valorAmortizacao' => 180.00,
                                'valorJuros' => 12.89,
                                'valorPrestacao' => 192.89,
                            ],
                            [
                                'numero' => 3,
                                'valorAmortizacao' => 180.00,
                                'valorJuros' => 9.67,
                                'valorPrestacao' => 189.67,
                            ],
                            [
                                'numero' => 4,
                                'valorAmortizacao' => 180.00,
                                'valorJuros' => 6.44,
                                'valorPrestacao' => 186.44,
                            ],
                            [
                                'numero' => 5,
                                'valorAmortizacao' => 180.00,
                                'valorJuros' => 3.22,
                                'valorPrestacao' => 183.22,
                            ],
                        ],
                    ],
                    [
                        'tipo' => 'PRICE',
                        'parcelas' => [
                            [
                                'numero' => 1,
                                'valorAmortizacao' => 173.67,
                                'valorJuros' => 16.11,
                                'valorPrestacao' => 189.78,
                            ],
                            [
                                'numero' => 2,
                                'valorAmortizacao' => 176.78,
                                'valorJuros' => 13.00,
                                'valorPrestacao' => 189.78,
                            ],
                            [
                                'numero' => 3,
                                'valorAmortizacao' => 179.94,
                                'valorJuros' => 9.84,
                                'valorPrestacao' => 189.78,
                            ],
                            [
                                'numero' => 4,
                                'valorAmortizacao' => 183.16,
                                'valorJuros' => 6.62,
                                'valorPrestacao' => 189.78,
                            ],
                            [
                                'numero' => 5,
                                'valorAmortizacao' => 186.44,
                                'valorJuros' => 3.34,
                                'valorPrestacao' => 189.78,
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_aplicacao_retorna_mensagem_de_erro_para_parametro_com_valor_indequado(): void
    {
        $response = $this->postJson('/', ['valorDesejado' => 900, 'prazo' => 96]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Erro ao processar a simulação',
                'data' => 'Parametros incompativeis com os produtos cadastrados.',
            ]);
    }

    public function test_aplicacao_retorna_mensagem_de_erro_para_parametro_ausente(): void
    {
        $response = $this->postJson('/', ['valorDesejado' => 900]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Erro ao validar os parametros fornecidos',
                'data' => ['prazo' => ['O parametro prazo é obrigatório']],
            ]);
    }
}
