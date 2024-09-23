<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeedbackSettingResource;
use App\Models\Feedback;
use App\Models\FeedbackSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedbackSettingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/feedbacks/settings",
     *     summary="Configuração do envio de feedback",	
     *     tags={"Feedbacks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a configuração de envio de feedback"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index()
    {
        $settings = FeedbackSetting::first();
        return new FeedbackSettingResource($settings);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/feedbacks/settings",
     *     summary="Configuração do envio de feedback",
     *     tags={"Feedbacks"},
     *     description="Utilize para atualizar as datas de envio do formulário de feedback e liberar para todos os alunos. Apenas o administrador pode alterar as datas de envio.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Configuração de envio de feedback atualizada com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"start_date", "end_date"},
     *              @OA\Property(property="start_date", type="date", example="2024-01-01"),
     *              @OA\Property(property="end_date", type="date", example="2025-01-01"),
     *              @OA\Property(property="is_enabled", type="boolean", example="true"),
     )
     *      ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $settings = FeedbackSetting::firstOrNew();
        $settings->start_date = $request->start_date;
        $settings->end_date = $request->end_date;
        $settings->is_enabled = true;
        $settings->save();

        // Notificar alunos (implementar a lógica de notificação aqui)

        return $this->response(
            'Configuração de envio de feedback atualizada com sucesso',
            Response::HTTP_OK,
            FeedbackSettingResource::make($settings)
        );
    }
}
