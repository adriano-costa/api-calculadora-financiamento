<?php

namespace App\Http\Controllers;

use App\Domain\Numeros\CastArrayComDecimaisService;
use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\MontaRespostaSimulacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SimulacaoController extends Controller
{
    public function __construct(private MontaRespostaSimulacaoService $service, private CastArrayComDecimaisService $castService)
    {
    }

    public function __invoke(Request $request)
    {
        //return $this->gerarAssinaturaEventHub();

        $parametros = $request->json()->all();
        $valorDesejado = new Dinheiro($parametros['valorDesejado']);
        $prazo = $parametros['prazo'];

        try {
            $resposta = $this->service->montarResposta($valorDesejado, $prazo);

            //Essa não é uma boa prática, mas foi feita para devido a especificação da tarefa
            $respostaEmFloat = $this->castService->tratarDecimaisParaFloat($resposta);

            $this->enviarParaEventHub($respostaEmFloat);

            return response()->json($respostaEmFloat);
        } catch (\Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 400);
        }
    }

    private function enviarParaEventHub(array $resposta): void
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
