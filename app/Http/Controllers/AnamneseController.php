<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReprovarAnamneseRequest;
use App\Http\Requests\StoreAnamneseRequest;
use App\Http\Requests\UpdateAnamneseRequest;
use App\Http\Resources\AnamneseResource;
use App\Mail\AnamneseReprovadaMail;
use App\Models\Aluno;
use App\Models\Anamnese;
use App\Models\User;
use App\Notifications\AnamneseReprovadaNotification;
use App\Notifications\PrimeiroAcessoNotification;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AnamneseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/anamnese",
     *     summary="Listar todas as anamneses",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="aprovadas",
     *         in="query",
     *         description="Filtra as anamneses com base na aprovação. Use 'false' para buscar apenas as não aprovadas.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"true", "false"},
     *             example="false"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Retorna a lista de anamneses"
     *     ),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index(Request $request)
    {
        $aprovada = $request->query('aprovadas');

        $anamneses = Anamnese::query();

        if ($aprovada === 'false') {
            $anamneses->where('is_aprovada', false);
        } elseif ($aprovada === 'true') {
            $anamneses->where('is_aprovada', true);
        }

        return AnamneseResource::collection($anamneses->get());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/anamnese",
     *     summary="Criar uma nova anamnese",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a anamnese criada"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="name", type="string", example="João da Silva"),
     *         @OA\Property(property="cpf", type="string", example="12345372920"),
     *         @OA\Property(property="email", type="string", example="joao.ss2ilvas@example.com"),
     *         @OA\Property(property="address", type="string", example="Rua das Flores, 123, Bairro Jardim"),
     *         @OA\Property(property="telefone", type="string", example="(11) 91234-5678"),
     *         @OA\Property(property="plano", type="string", example="semestral"),
     *         @OA\Property(property="vencimento", type="string", format="date", example="2025-12-31"),
     *         @OA\Property(property="birth_date", type="string", format="date", example="1990-05-15"),
     *         @OA\Property(property="altura_peso", type="string", example="1.75m, 70kg"),
     *         @OA\Property(property="rotina", type="string", example="Trabalho das 9h às 18h, faço academia à noite."),
     *         @OA\Property(property="indicacao", type="string", example="Indicado por um amigo."),
     *         @OA\Property(property="profissao", type="string", example="Engenheiro"),
     *         @OA\Property(property="objetivo", type="string", example="Perder peso"),
     *         @OA\Property(property="acompanhamento_anterior", type="string", example="Nutricionista"),
     *         @OA\Property(property="refeicoes_por_dia", type="string", example="3"),
     *         @OA\Property(property="quantas_vezes_pode_comer", type="string", example="5"),
     *         @OA\Property(property="agua_por_dia", type="string", example="2 litros"),
     *         @OA\Property(property="horario_fome", type="string", example="11h e 17h"),
     *         @OA\Property(property="cafe_manha", type="string", example="Pão integral e suco"),
     *         @OA\Property(property="almoco", type="string", example="Arroz, feijão e frango"),
     *         @OA\Property(property="entre_almoco_jantar", type="string", example="Fruta e iogurte"),
     *         @OA\Property(property="jantar", type="string", example="Salada e carne grelhada"),
     *         @OA\Property(property="beliscar", type="string", example="Frutas secas"),
     *         @OA\Property(property="mais_alguma_ref", type="string", example="Às vezes uma sobremesa"),
     *         @OA\Property(property="alimento_beliscar", type="string", example="Nozes"),
     *         @OA\Property(property="alimentos_dia_dia", type="string", example="Legumes, frutas e carnes"),
     *         @OA\Property(property="nao_alimentos_dia_dia", type="string", example="Fast food"),
     *         @OA\Property(property="refeicao_pratica", type="string", example="Marmitas prontas"),
     *         @OA\Property(property="balanca", type="string", example="Sim"),
     *         @OA\Property(property="airfryer_bolsa", type="string", example="Sim"),
     *         @OA\Property(property="alergia", type="string", example="Nenhuma"),
     *         @OA\Property(property="bebida_alcoolica", type="string", example="Socialmente"),
     *         @OA\Property(property="peso_1ano_3anos", type="string", example="Estável"),
     *         @OA\Property(property="doce_salgados", type="string", example="Doces"),
     *         @OA\Property(property="relacao_comida", type="string", example="Normal"),
     *         @OA\Property(property="fazcoco", type="string", example="Regularmente"),
     *         @OA\Property(property="horas_sono", type="string", example="7 horas"),
     *         @OA\Property(property="nivel_treino", type="string", example="Intermediário"),
     *         @OA\Property(property="dias_treino", type="string", example="4"),
     *         @OA\Property(property="divisao_treino", type="string", example="Musculação e cardio"),
     *         @OA\Property(property="aparelhos_academia", type="string", example="Sim"),
     *         @OA\Property(property="plano_saude", type="string", example="Sim"),
     *         @OA\Property(property="nivel_saude", type="string", example="Bom"),
     *         @OA\Property(property="tem_medico", type="string", example="Sim"),
     *         @OA\Property(property="fumante", type="string", example="Não"),
     *         @OA\Property(property="medicacoes", type="string", example="Nenhuma"),
     *         @OA\Property(property="doencas_gastrointestinal", type="string", example="Não"),
     *         @OA\Property(property="doencas_cardiovascular", type="string", example="Não"),
     *         @OA\Property(property="doencas_osseas", type="string", example="Não"),
     *         @OA\Property(property="doenca_autoimune", type="string", example="Não"),
     *         @OA\Property(property="doenca_respiratoria", type="string", example="Não"),
     *         @OA\Property(property="doenca_neurologico", type="string", example="Não"),
     *     )
     * )
     * )
     */
    public function store(StoreAnamneseRequest $request)
    {
        //dd($request->all());
        $data = $request->validated();
        $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

        try {

            if (Anamnese::where('cpf', $data['cpf'])->exists() || User::where('cpf', $data['cpf'])->exists() || User::where('cpf', $request->cpf)->exists()) {
                return $this->error('Já existe uma anamnese para este CPF', 400);
            }

            $anamnese = Anamnese::create($data);

            return AnamneseResource::make($anamnese);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error('Já existe uma anamnese para este CPF ou E-mail', 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/anamnese/{cpf}",
     *     summary="Mostrar uma anamnese pelo cpf",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna uma anamnese pelo cpf"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="cpf",
     *          in="path",
     *          description="Cpf do aluno",
     *          required=true,
     *         )
     * )
     */
    public function show(string $cpf)
    {
        try {
            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            $anamnese = Anamnese::where('cpf', $cpf)->firstOrFail();
            return AnamneseResource::make($anamnese);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return $this->error("Anamnese não encontrada para o CPF: $cpf", 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/anamnese/{cpf}",
     *     summary="Atualizar uma anamnese",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="cpf",
     *         in="path",
     *         description="CPF do aluno",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="name", type="string", example="João da Silva"),
     *         @OA\Property(property="cpf", type="string", example="12345372920"),
     *         @OA\Property(property="email", type="string", example="joao.ss2ilvas@example.com"),
     *         @OA\Property(property="address", type="string", example="Rua das Flores, 123, Bairro Jardim"),
     *         @OA\Property(property="telefone", type="string", example="(11) 91234-5678"),
     *         @OA\Property(property="plano", type="string", example="semestral"),
     *         @OA\Property(property="vencimento", type="string", format="date", example="2025-12-31"),
     *         @OA\Property(property="birth_date", type="string", format="date", example="1990-05-15"),
     *         @OA\Property(property="altura_peso", type="string", example="1.75m, 70kg"),
     *         @OA\Property(property="rotina", type="string", example="Trabalho das 9h às 18h, faço academia à noite."),
     *         @OA\Property(property="indicacao", type="string", example="Indicado por um amigo."),
     *         @OA\Property(property="profissao", type="string", example="Engenheiro"),
     *         @OA\Property(property="objetivo", type="string", example="Perder peso"),
     *         @OA\Property(property="acompanhamento_anterior", type="string", example="Nutricionista"),
     *         @OA\Property(property="refeicoes_por_dia", type="string", example="3"),
     *         @OA\Property(property="quantas_vezes_pode_comer", type="string", example="5"),
     *         @OA\Property(property="agua_por_dia", type="string", example="2 litros"),
     *         @OA\Property(property="horario_fome", type="string", example="11h e 17h"),
     *         @OA\Property(property="cafe_manha", type="string", example="Pão integral e suco"),
     *         @OA\Property(property="almoco", type="string", example="Arroz, feijão e frango"),
     *         @OA\Property(property="entre_almoco_jantar", type="string", example="Fruta e iogurte"),
     *         @OA\Property(property="jantar", type="string", example="Salada e carne grelhada"),
     *         @OA\Property(property="beliscar", type="string", example="Frutas secas"),
     *         @OA\Property(property="mais_alguma_ref", type="string", example="Às vezes uma sobremesa"),
     *         @OA\Property(property="alimento_beliscar", type="string", example="Nozes"),
     *         @OA\Property(property="alimentos_dia_dia", type="string", example="Legumes, frutas e carnes"),
     *         @OA\Property(property="nao_alimentos_dia_dia", type="string", example="Fast food"),
     *         @OA\Property(property="refeicao_pratica", type="string", example="Marmitas prontas"),
     *         @OA\Property(property="balanca", type="string", example="Sim"),
     *         @OA\Property(property="airfryer_bolsa", type="string", example="Sim"),
     *         @OA\Property(property="alergia", type="string", example="Nenhuma"),
     *         @OA\Property(property="bebida_alcoolica", type="string", example="Socialmente"),
     *         @OA\Property(property="peso_1ano_3anos", type="string", example="Estável"),
     *         @OA\Property(property="doce_salgados", type="string", example="Doces"),
     *         @OA\Property(property="relacao_comida", type="string", example="Normal"),
     *         @OA\Property(property="fazcoco", type="string", example="Regularmente"),
     *         @OA\Property(property="horas_sono", type="string", example="7 horas"),
     *         @OA\Property(property="nivel_treino", type="string", example="Intermediário"),
     *         @OA\Property(property="dias_treino", type="string", example="4"),
     *         @OA\Property(property="divisao_treino", type="string", example="Musculação e cardio"),
     *         @OA\Property(property="aparelhos_academia", type="string", example="Sim"),
     *         @OA\Property(property="plano_saude", type="string", example="Sim"),
     *         @OA\Property(property="nivel_saude", type="string", example="Bom"),
     *         @OA\Property(property="tem_medico", type="string", example="Sim"),
     *         @OA\Property(property="fumante", type="string", example="Não"),
     *         @OA\Property(property="medicacoes", type="string", example="Nenhuma"),
     *         @OA\Property(property="doencas_gastrointestinal", type="string", example="Não"),
     *         @OA\Property(property="doencas_cardiovascular", type="string", example="Não"),
     *         @OA\Property(property="doencas_osseas", type="string", example="Não"),
     *         @OA\Property(property="doenca_autoimune", type="string", example="Não"),
     *         @OA\Property(property="doenca_respiratoria", type="string", example="Não"),
     *         @OA\Property(property="doenca_neurologico", type="string", example="Não"),
     *     )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Retorna a anamnese atualizada"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Anamnese não encontrada"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro interno do servidor"
     *     )
     * )
     */

    public function update(UpdateAnamneseRequest $request, string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        try {
            $anamnese = Anamnese::where('cpf', $cpf)->firstOrFail();
            $data = $request->validated();
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

            if (User::where('cpf', $data['cpf'])->exists() || User::where('cpf', $request->cpf)->exists()) {
                return $this->error('Já existe uma anamnese para este CPF', 400);
            }

            $anamnese->update($data);
            return AnamneseResource::make($anamnese);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return $this->error("Anamnese não encontrada para o CPF: $cpf", 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anamnese $anamnese)
    {
        //
    }
    /**
     * @OA\Post(
     *     path="/api/v1/anamnese/aprovar/{id}",
     *     summary="Aprovar uma anamnese",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     description="OBS: Se uma anamnese for aprovada, será criado um novo aluno com os dados fornecidos no formulário de anamnese.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da anamnese",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Anamnese aprovada com sucesso"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Anamnese já foi aprovada"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Anamnese não encontrada"
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
    public function aprovarAnamnese(string $id)
    {
        $anamnese = Anamnese::where('id', $id)->firstOrFail();

        if ($anamnese->is_aprovada) {
            return $this->error('Anamnese já foi aprovada', 400);
        }

        $alunoData = Arr::only($anamnese->toArray(), ['plano', 'vencimento', 'status']);
        $alunoData['status'] = true;
        $alunoData['anamnese_id'] = $anamnese->id;

        $aluno = Aluno::create($alunoData);

        $aluno->user()->create($anamnese->toArray() + ['password' => Hash::make($anamnese->cpf)] + ['cpf' => $anamnese->cpf]);

        $anamnese->update(['is_aprovada' => true]);
        $anamnese->save();

        $aluno->user->notify(new PrimeiroAcessoNotification($aluno->user));
        return $this->response('Anamnese aprovada com sucesso', Response::HTTP_OK);
    }
    /**
     * @OA\Post(
     *     path="/api/v1/anamnese/reprovar/{id}",
     *     summary="Reprovar uma anamnese",
     *     tags={"Anamneses"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da anamnese",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="motivo_reprovacao", type="string", example="O campo nome é obrigatorio"),
     *     )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Anamnese reprovada com sucesso"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Anamnese não encontrada"
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

    public function reprovarAnamnese(ReprovarAnamneseRequest $request, string $id)
    {
        $anamnese = Anamnese::where('id', $id)->firstOrFail();
        $anamnese->update([
            'is_aprovada' => false,
            'motivo_reprovacao' => $request->motivo_reprovacao
        ]);
        
        $anamnese->save();
        Mail::to($anamnese->email)->send(new AnamneseReprovadaMail($anamnese));

        return $this->response('Anamnese reprovada', Response::HTTP_OK);
    }
}
