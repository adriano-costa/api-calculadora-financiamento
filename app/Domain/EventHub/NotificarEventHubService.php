<?php

namespace App\Domain\EventHub;

use Illuminate\Support\Facades\Http;

class NotificarEventHubService
{
    public function notificar(array $resposta): void
    {
        $host = config('eventhub.host');
        $entity = config('eventhub.entity_path');
        $url = 'https://'.$host.'/'.$entity.'/messages';

        $respostaStringJson = json_encode($resposta);

        $resultado = Http::withoutVerifying() // Não verificar o certificado SSL, necessário para execução na intranet
            ->withHeaders([
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
        $host = config('eventhub.host');
        $entity = config('eventhub.entity_path');
        $uri = $host.'/'.$entity;
        $sasKeyName = config('eventhub.sas_key_name');
        $sasKeyValue = config('eventhub.sas_key');

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
