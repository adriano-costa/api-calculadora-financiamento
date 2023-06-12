<?php

namespace App\Http\Controllers;

use App\Domain\Numeros\CastArrayComDecimaisService;
use App\Domain\Numeros\Dinheiro;
use App\Domain\Produtos\MontaRespostaSimulacaoService;
use App\Http\Requests\SimulacaoRequest;
use App\Jobs\ProcessEventHubProducer;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Info(
 *      version="1.0",
 *      title="Documentação da API de Simulação de Financiamento",
 *      description="Projeto simples que implementa uma API para uma calculadora de financiamentos.",
 *
 *      @OA\Contact(
 *          email="adriano.dmcosta@gmail.com"
 *      ),
 * )
 */
class SimulacaoController extends Controller
{
    public function __construct(private MontaRespostaSimulacaoService $service, private CastArrayComDecimaisService $castService)
    {
    }

    /**
     * @OA\Post(
     *      path="/v1/simulacao",
     *      tags={"Simulacao"},
     *      summary="Simular um financiamento",
     *      description="Retorna os dados da simulação de um financiamento segundo os parâmetros informados. São calculados os sistemas de amortização SAC e PRICE.",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *             required={"valorDesejado", "prazo"},
     *
     *             @OA\Property(property="valorDesejado", type="number", format="float", example="200.00"),
     *             @OA\Property(property="prazo", type="integer", format="int32", example="1")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="codigoProduto", type="number", format="int", example="1"),
     *              @OA\Property(property="descricaoProduto", type="string", example="Produto 6"),
     *              @OA\Property(property="taxaJuros", type="number", format="float", example="0.0179"),
     *              @OA\Property(property="resultadoSimulacao", type="array",
     *
     *                  @OA\Items(
     *
     *                      @OA\Property(property="tipo", type="string", example="SAC"),
     *                      @OA\Property(property="parcelas", type="array",
     *
     *                          @OA\Items(
     *
     *                              @OA\Property(property="numero", type="integer", format="int32", example="1"),
     *                              @OA\Property(property="valorAmortizacao", type="number", format="float", example="200.00"),
     *                              @OA\Property(property="valorJuros", type="number", format="float", example="3.58"),
     *                              @OA\Property(property="valorPrestacao", type="number", format="float", example="203.58"),
     *                          )
     *                      )
     *                  )
     *             )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação dos parametros"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Erro ao processar a simulação"
     *      )
     *)
     */
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
            ProcessEventHubProducer::dispatchAfterResponse($respostaEmFloat);

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
