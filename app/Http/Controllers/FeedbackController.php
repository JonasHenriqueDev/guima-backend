<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Aluno;
use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
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
        // try {
        $feedbacks = Feedback::paginate(10);
        return FeedbackResource::collection($feedbacks);
        // } catch (Exception $e) {
        //     Log::error(self::INTERNAL_SERVER_ERROR);
        //     return $this->error(self::INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
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
        $data = $request->validated();
        $user = auth()->user();
        $aluno = Aluno::where('id', $user->profile_id)->first();

        // if ($aluno->is_new_user) {
        //     return $this->error('Feedback indisponível para novos usuários, realize a anamnese primeiro.', Response::HTTP_FORBIDDEN);
        // }
        // if ($aluno->data_feedback_inicio >= Carbon::now()->format('Y-m-d') || $aluno->data_feedback_fim <= Carbon::now()->format('Y-m-d')) {
        //     return $this->error('Feedback indisponível no momento', Response::HTTP_FORBIDDEN);
        // }
        $data['aluno_id'] = $aluno->id;
        Feedback::create($data);

        return FeedbackResource::make($data);
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
}
