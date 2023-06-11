<?php

namespace App\Jobs;

use App\Domain\EventHub\EventHubProducerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEventHubProducer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $simulacao;

    /**
     * Create a new job instance.
     */
    public function __construct(string $simulacao)
    {
        $this->simulacao = $simulacao;
    }

    /**
     * Execute the job.
     */
    public function handle(EventHubProducerService $service): void
    {
        $service->enviarEvento($this->simulacao);
    }
}
