<?php

use App\Http\Controllers\SimulacaoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/', SimulacaoController::class);

Route::get('/simulacoes', function () {
    $conf = new \RdKafka\Conf();
    $conf->set('group.id', 'hack');
    $conf->set('metadata.broker.list', 'eventhack.servicebus.windows.net:9093');
    $conf->set('security.protocol', 'SASL_SSL');
    $conf->set('sasl.mechanisms', 'PLAIN');
    $conf->set('sasl.username', '$ConnectionString'); // exactly as you see it
    $conf->set('sasl.password', 'Endpoint=sb://eventhack.servicebus.windows.net/;SharedAccessKeyName=hack;SharedAccessKey=HeHeVaVqyVkntO2FnjQcs2Ilh/4MUDo4y+AEhKp8z+g=;EntityPath=simulacoes'); // should look like: Endpoint=sb://sp-event-hubs.servicebus.windows.net/;SharedAccessKeyName=RootManageSharedAccessKey;SharedAccessKey=someStringHere
    $conf->set('enable.partition.eof', 'true');
    // $conf->set('api.version.request', 'false');
    // $conf->set('log_level', (string) LOG_DEBUG);
    // $conf->set('debug', 'all');
    $conf->set('auto.offset.reset', 'latest');

    $consumer = new RdKafka\KafkaConsumer($conf);

    // Subscribe to topic 'test'
    $consumer->subscribe(['simulacoes']);
    while (true) {
        $message = $consumer->consume(60 * 1000);
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                var_dump($message);
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                echo "No more messages; will wait for more\n";
                exit();
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                echo "Timed out\n";
                exit();
                break;
            default:
                throw new \Exception($message->errstr(), $message->err);
                break;
        }
    }

    $producer = new \RdKafka\Producer($conf);
    $producer->purge(RD_KAFKA_PURGE_F_QUEUE);
    $result = $producer->flush(10000);
});
