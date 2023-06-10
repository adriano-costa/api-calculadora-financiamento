<?php

namespace App\Domain\EventHub;

class NotificarEventHubAmqpService
{
    public function notificar(array $resposta): void
    {
        $conf = new \RdKafka\Conf();
        // $conf->set('group.id', 'your-consumer-group-name');
        $conf->set('metadata.broker.list', 'eventhack.servicebus.windows.net:9093');
        $conf->set('security.protocol', 'SASL_SSL');
        $conf->set('sasl.mechanisms', 'PLAIN');
        $conf->set('sasl.username', '$ConnectionString'); // exactly as you see it
        $conf->set('sasl.password', 'Endpoint=sb://eventhack.servicebus.windows.net/;SharedAccessKeyName=hack;SharedAccessKey=HeHeVaVqyVkntO2FnjQcs2Ilh/4MUDo4y+AEhKp8z+g=;EntityPath=simulacoes'); // should look like: Endpoint=sb://sp-event-hubs.servicebus.windows.net/;SharedAccessKeyName=RootManageSharedAccessKey;SharedAccessKey=someStringHere
        // $conf->set('enable.partition.eof', 'true');
        // $conf->set('api.version.request', 'false');
        // $conf->set('log_level', (string) LOG_DEBUG);
        // $conf->set('debug', 'all');

        $producer = new \RdKafka\Producer($conf);

        $topic = $producer->newTopic('simulacoes');

        $respostaStringJson = json_encode($resposta);

        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $respostaStringJson);
        $producer->poll(0);

        $result = $producer->flush(10000);

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new \RuntimeException('Was unable to flush, messages might be lost!');
        }

    }
}
