<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProtocoloRequest;
use App\Http\Requests\UpdateProtocoloRequest;
use App\Http\Resources\ProtocoloResource;
use App\Models\Aluno;
use App\Models\Protocolo;
use App\Models\User;
use Illuminate\Http\Request;

class ProtocoloController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/alunos/protocolos",
     *     summary="Listar todos os protocolos de todos os alunos",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}}, 
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por status do protocolo (ex: aprovado, pendente, reprovado)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="Filtrar pelos protocolos de um aluno com CPF",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="data_inicial",
     *         in="query",
     *         description="Data inicial para filtrar os protocolos (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="data_final",
     *         in="query",
     *         description="Data final para filtrar os protocolos (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Direção de ordenação por data (asc ou desc, padrão: asc)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(response="200", description="Retorna a lista de protocolos de todos os alunos"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index(Request $request)
    {
        $query = Protocolo::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('cpf')) {
            $usuario = User::where('cpf', $request->input('cpf'))->first();

            if ($usuario && $usuario->profile_type == 'App\Models\Aluno') {
                $query->where('aluno_id', $usuario->profile_id);
            } else {
                $query->whereNull('aluno_id');
            }
        }

        if ($request->has('data_inicial') && $request->has('data_final')) {
            $query->whereBetween('created_at', [$request->input('data_inicial'), $request->input('data_final')]);
        }

        $orderDirection = $request->input('order', 'asc');
        $query->orderBy('created_at', $orderDirection);

        $protocolos = $query->paginate();

        return ProtocoloResource::collection($protocolos);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/protocolos/my",
     *     summary="Listar todos os protocolos do aluno logado",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de protocolos do aluno logado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function showMyProtocolos()
    {
        if (auth()->user()->profile_type != 'App\Models\Aluno') {
            return response()->json(['message' => 'Formulário protocolo disponível apenas para alunos'], 403);
        }

        $aluno = Aluno::where('id', auth()->user()->profile_id)->first();

        $protocolos = $aluno->protocolo()->paginate();
        return ProtocoloResource::collection($protocolos);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/protocolos/{alunoId}",
     *     summary="Enviar um novo protocolo",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     description="Utilize um Content-Type: multipart/form-data para enviar o PDF do protocolo",
     *     @OA\Response(response="200", description="Protocolo enviado com sucesso."),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="alunoId",
     *          in="path",
     *          description="Id do aluno",
     *          required=true,
     *         ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="pdf", type="file", example="protocolo.pdf"),
     *          )
     *      ),
     * )
     */
    public function store(StoreProtocoloRequest $request, string $alunoId)
    {
        $request->validated();

        $filePath = $request->file('pdf')->store('protocolos');

        $protocolo = new Protocolo();
        $protocolo->aluno_id = $alunoId;
        $protocolo->file_path = $filePath;

        $protocolo->save();

        return response()->json(['message' => 'Protocolo enviado com sucesso.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/protocolos/{alunoId}/reenviar",
     *     summary="Reenviar um protocolo",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     description="Utilize um Content-Type: multipart/form-data para enviar o PDF do protocolo",
     *     @OA\Response(response="200", description="Protocolo reenviado com sucesso. Aguardando aprovação do aluno."),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="alunoId",
     *          in="path",
     *          description="Id do aluno",
     *          required=true,
     *         ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="pdf", type="file", example="protocolo.pdf"),
     *              @OA\Property(property="justificativa", type="string", example="Motivo do reenvio"),
     *          )
     *      ),
     * )
     */
    public function resend(UpdateProtocoloRequest $request, string $alunoId)
    {
        $request->validated();

        $filePath = $request->file('pdf')->store('protocolos');

        $protocolo = Protocolo::where('aluno_id', $alunoId)->latest()->first();
        $protocolo->file_path = $filePath;
        $protocolo->justificativa = $request->justificativa;
        $protocolo->staus = 'pendente';
        $protocolo->save();

        return response()->json(['message' => 'Protocolo reenviado com sucesso. Aguardando aprovação do aluno.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/protocolos/aprovar/{id}",
     *     summary="Aprovar um protocolo",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     description="Aprovar um protocolo existente.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do protocolo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Protocolo aprovado com sucesso"
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Protocolo já foi aprovado"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Protocolo não encontrado"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro interno do servidor"
     *     ),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function aprovar(string $id)
    {
        $protocolo = Protocolo::findOrFail($id);

        if ($protocolo->status == 'aprovado') {
            return response()->json(['message' => 'Protocolo ja foi aprovado.'], 409);
        }


        $protocolo->status = 'aprovado';
        $protocolo->update();

        return response()->json(['message' => 'Protocolo aprovado com sucesso.'], 200);
    }
    /**
     * @OA\Post(
     *     path="/api/v1/protocolos/reprovar/{id}",
     *     summary="Reprovar um protocolo",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     description="Reprovar um protocolo existente com justificativa.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do protocolo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="justificativa", type="string", example="Motivo do reenvio"),
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Protocolo reprovado com sucesso"
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Protocolo já foi reprovado"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Protocolo não encontrado"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro interno do servidor"
     *     ),
     *     security={
     *          { "apiAuth": {} }
     *   }
     * )
     */
    public function reprovar(string $id, Request $request)
    {
        $protocolo = Protocolo::findOrFail($id);

        if ($protocolo->status == 'reprovado') {
            return response()->json(['message' => 'Protocolo ja foi reprovado. Aguarde o retorno do administrador.'], 409);
        }

        $protocolo->status = 'reprovado';
        $protocolo->justificativa = $request->justificativa;
        $protocolo->update();

        return response()->json(['message' => 'Protocolo reprovado com sucesso.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/protocolos/download/{id}",
     *     summary="Baixar um protocolo",
     *     tags={"Protocolos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do protocolo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Retorna o arquivo do protocolo"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Arquivo não encontrado"
     *     ),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function download(string $id)
    {
        $protocolo = Protocolo::findOrFail($id);

        $filePath = storage_path('app/' . $protocolo->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'Arquivo não encontrado.'], 404);
        }

        return response()->download($filePath);
    }
}
