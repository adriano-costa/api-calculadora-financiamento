<?php

namespace App\Http\Controllers;

use App\Domain\Numeros\CastArrayComDecimaisService;
use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\MontaRespostaSimulacaoService;
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{
    public function __construct(private MontaRespostaSimulacaoService $service, private CastArrayComDecimaisService $castService)
    {
    }

    public function __invoke(Request $request)
    {
        $parametros = $request->json()->all();
        $valorDesejado = new Dinheiro($parametros['valorDesejado']);
        $prazo = $parametros['prazo'];

        try {
            $resposta = $this->service->montarResposta($valorDesejado, $prazo);

            //Essa não é uma boa prática, mas foi feita para devido a especificação da tarefa
            $respostaEmFloat = $this->castService->tratarDecimaisParaFloat($resposta);

            return response()->json($respostaEmFloat);
        } catch (\Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 400);
        }
    }
}
