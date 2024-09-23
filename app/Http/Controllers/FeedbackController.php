<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Aluno;
use App\Models\Feedback;
use App\Models\FeedbackSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Response;

class FeedbackController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/feedbacks",
     *     summary="Listar todos os feedbacks",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de feedbacks"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index()
    {
        try {
            $feedbacks = Feedback::paginate(10);
            return FeedbackResource::collection($feedbacks);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/feedbacks",
     *     summary="Criar um novo feedback",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Feedback criado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="descricao", type="string", example="Feedback de teste"),
     *          )
     *      ),
     * )
     */
    public function store(StoreFeedbackRequest $request)
    {
        $user = auth()->user();

        if ($user->profile_type != 'App\Models\Aluno') {
            return $this->error('Formulário feedback disponível apenas para alunos', Response::HTTP_FORBIDDEN);
        }

        $data = $request->validated();
        $aluno = Aluno::where('id', $user->profile_id)->first();

        if ($aluno->is_new_user) {
            return $this->error('Formulário feedback indisponível para novos usuários, realize a anamnese primeiro.', Response::HTTP_FORBIDDEN);
        }

        if (
            Feedback::where('aluno_id', $aluno->id)->exists() &&
            Feedback::where('aluno_id', $aluno->id)->where('is_aprovado', false)->exists()
        ) {
            return $this->error('Já existe um feedback pendente para este aluno', Response::HTTP_BAD_REQUEST);
        }


        $settings = FeedbackSetting::first();

        if ($settings->is_enabled == false || $settings->start_date > Carbon::now() || $settings->end_date < Carbon::now()) {
            return $this->error('Formulário de feedback indisponível no momento', Response::HTTP_FORBIDDEN);
        }

        $data['aluno_id'] = $aluno->id;
        $feedback = Feedback::create($data);

        return FeedbackResource::make($feedback);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/feedbacks/{id}",
     *     summary="Mostrar um feedback específico por id",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o feedback específico por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do feedback",
     *          required=true,
     *         )
     * )
     */
    public function show(Feedback $feedback)
    {
        try {
            return new FeedbackResource($feedback);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/feedbacks/{id}",
     *     summary="Atualizar um feedback específico por id",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o feedback atualizado por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="feedback",
     *          in="path",
     *          description="Id do feedback",
     *          required=true,
     *         ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="descricao", type="string", example="Feedback de teste"),
     *          )
     *      ),
     * )
     */
    public function update(UpdateFeedbackRequest $request, Feedback $feedback)
    {
        if ($feedback->is_aprovado) {
            return $this->error('Feedback deste aluno já aprovado. Já não pode ser editado.', Response::HTTP_BAD_REQUEST);
        }

        try {
            $feedback->update($request->all());
            return new FeedbackResource($feedback);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/feedbacks/{id}",
     *     summary="Deletar um feedback específico por id",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Feedback deletado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do feedback",
     *          required=true,
     *         )
     * )
     */
    public function destroy(Feedback $feedback)
    {
        try {
            $feedback->delete();
            return $this->response('Feedback deletado com sucesso', Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            Log::error(self::INTERNAL_SERVER_ERROR);
            return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/feedbacks/aprovar/{id}",
     *     summary="Aprovar um feedback específico por id",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Feedback aprovado com sucesso. O aluno será notificado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do feedback",
     *          required=true,
     *         )
     * )
     */
    public function aprovarFeedback(string $id)
    {
        $feedback = Feedback::findOrFail($id);

        if ($feedback->is_aprovado) {
            return $this->error('Feedback deste aluno já aprovado.', Response::HTTP_BAD_REQUEST);
        }

        $feedback->is_aprovado = true;
        $feedback->save();

        return $this->response('Feedback aprovado com sucesso. O aluno será notificado', Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/feedbacks/reprovar/{id}",
     *     summary="Reprovar um feedback específico por id",
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Feedback reprovado com sucesso. O aluno será notificado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do feedback",
     *          required=true,
     *         )
     * )
     */
    public function reprovarFeedback(string $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->is_aprovado = false;
        $feedback->save();

        //notificar aluno
        return $this->response('Feedback reprovado com sucesso. O aluno será notificado', Response::HTTP_OK);
    }
}
