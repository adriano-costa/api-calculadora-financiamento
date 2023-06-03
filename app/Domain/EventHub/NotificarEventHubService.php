<?php

namespace App\Domain\EventHub;

use Illuminate\Support\Facades\Http;

class NotificarEventHubService
{
    public function notificar(array $resposta): void
    {
        $host = env('EVENT_HUB_HOST');
        $url = 'https://'.env('EVENT_HUB_HOST').'/'.env('EVENT_HUB_ENTITY_PATH').'/messages';

        $respostaStringJson = json_encode($resposta);

        $resultado = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $this->gerarAssinaturaEventHub(),
            'Host' => $host,
            'Content-Length' => strlen($respostaStringJson),
        ])
            ->post($url, $respostaStringJson);

        if ($resultado->failed()) {
            throw new \Exception('Erro: '.$resultado->body());
        }

        if ($resultado->status() != 201) {
            throw new \Exception('Erro: Aplicação Aplicação não conseguiu enviar a mensagem para o EventHub. Código de erro: '.$resultado->status().'. Mensagem: '.$resultado->body().'.');
        }
    }

    private function gerarAssinaturaEventHub()
    {
        $sasKeyName = env('EVENT_HUB_SAS_KEY_NAME');
        $uri = env('EVENT_HUB_HOST').'/'.env('EVENT_HUB_ENTITY_PATH');  //'eventhack.servicebus.windows.net/simulacoes';
        $sasKeyValue = env('EVENT_HUB_SAS_KEY');

        return $this->generateSasToken($uri, $sasKeyName, $sasKeyValue);
    }

    /**
     * Gera um token de acesso para o EventHub.
     * Código obtido em https://learn.microsoft.com/en-us/rest/api/eventhub/generate-sas-token#php
     */
    private function generateSasToken($uri, $sasKeyName, $sasKeyValue): string
    {
        $targetUri = strtolower(rawurlencode(strtolower($uri)));
        $expires = time();
        $expiresInMins = 60;
        $week = 60 * 60 * 24 * 7;
        $expires = $expires + $week;
        $toSign = $targetUri."\n".$expires;
        $signature = rawurlencode(base64_encode(hash_hmac('sha256',
            $toSign, $sasKeyValue, true)));

        $token = 'SharedAccessSignature sr='.$targetUri.'&sig='.$signature.'&se='.$expires.'&skn='.$sasKeyName;

        return $token;
    }
}
