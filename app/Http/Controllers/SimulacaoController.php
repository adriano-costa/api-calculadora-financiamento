<?php

namespace App\Http\Controllers;

use App\Domain\EventHub\EventHubProducerService;
use App\Domain\Numeros\CastArrayComDecimaisService;
use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\MontaRespostaSimulacaoService;
use App\Http\Requests\SimulacaoRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SimulacaoController extends Controller
{
    public function __construct(private MontaRespostaSimulacaoService $service, private CastArrayComDecimaisService $castService, private EventHubProducerService $EventHubProducerService)
    {
    }

    public function __invoke(SimulacaoRequest $request)
    {
        $parametros = $request->validated();

        $valorDesejado = new Dinheiro($parametros['valorDesejado']);
        $prazo = $parametros['prazo'];

        try {
            $resposta = $this->service->montarResposta($valorDesejado, $prazo);

            //Essa não é uma boa prática, mas foi feita para devido a especificação da tarefa.
            //Valores monetários deveriam ser representados como inteiros ou como strings
            $respostaEmFloat = $this->castService->tratarDecimaisParaFloat($resposta);

            //enviar a simulação para o EventHub
            $this->EventHubProducerService->enviarEvento($respostaEmFloat);

            return response()->json($respostaEmFloat);
        } catch (\Exception $e) {
            throw new HttpResponseException($this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao processar a simulação',
                'data' => $e->getMessage(),
            ], 400));
        }
    }
}
