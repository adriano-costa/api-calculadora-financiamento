<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessEventHubProducer;
use Tests\TestCase;

class ProcessEventHubTest extends TestCase
{
    public function test_job_process_eventhub_chama_o_service()
    {
        $this->mock(\App\Domain\EventHub\EventHubProducerService::class, function ($mock) {
            $mock->shouldReceive('enviarEvento')->once();
        });

        ProcessEventHubProducer::dispatch(['fake' => 'data']);
    }
}
