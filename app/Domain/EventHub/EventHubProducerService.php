<?php

namespace App\Domain\EventHub;

use Illuminate\Support\Facades\Http;

class EventHubProducerService
{
    private string $host;
    private string $entity;
    private string $url;
    private string $sasKeyName;
    private string $sasKeyValue;


    function __construct()
    {
        $this->host = config('eventhub.host');
        $this->entity = config('eventhub.entity_path');
        $this->url = 'https://'.$this->host.'/'.$this->entity.'/messages';
        $this->sasKeyName = config('eventhub.sas_key_name');
        $this->sasKeyValue = config('eventhub.sas_key');
    }

    public function enviarEvento(string $eventoPayload): void
    {
        $requisicaoPendente = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $this->gerarAssinaturaEventHub(),
            'Host' => $this->host,
            'Content-Length' => strlen($eventoPayload),
        ]);

        if (! config('eventhub.validar_certificado_ssl')) {
            $requisicaoPendente->withoutVerifying();
        }

        $requisicao = $requisicaoPendente->post($this->url, $eventoPayload);

        if ($requisicao->failed()) {
            throw new \Exception('Falha no processo de requisição http ao EventHub. Corpo da requisição devolvida: '.$requisicao->body());
        }

        if ($requisicao->status() != 201) {
            throw new \Exception('Erro: Aplicação Aplicação não conseguiu enviar a mensagem para o EventHub. Código de erro: '.$requisicao->status().'. Mensagem: '.$requisicao->body().'.');
        }
    }

    private function gerarAssinaturaEventHub()
    {
        $uri = $this->host.'/'.$this->entity;

        return $this->generateSasToken($uri, $this->sasKeyName, $this->sasKeyValue);
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
