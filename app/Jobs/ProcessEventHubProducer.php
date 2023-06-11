<?php

namespace App\Jobs;

use App\Domain\EventHub\EventHubProducerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEventHubProducer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $simulacao;

    /**
     * Create a new job instance.
     */
    public function __construct(array $simulacaoArray)
    {
        $this->simulacao = json_encode($simulacaoArray);
    }

    /**
     * Execute the job.
     */
    public function handle(EventHubProducerService $service): void
    {
        try {
            $service->enviarEvento($this->simulacao);
        } catch (\Exception $e) {
            Log::error('Erro ao tentar enviar a simulação para o EventHub',
                [
                    'simulacao' => $this->simulacao,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );
        }
    }
}
