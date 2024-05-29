<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\User;
use App\Services\AlunoService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    use HttpResponses;
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Realizar login no sistema",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o token de acesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *    @OA\RequestBody(
     *        required=true,
     *       @OA\JsonContent(
     *          required={"email", "password"},
     *          @OA\Property(property="email", type="string", format="email", example="admin@email.com"),
     *          @OA\Property(property="password", type="string", format="password", example="password"),
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $email = $request->email;

        $profile_type = User::where('email', $email)->firstOrFail()->profile_type;

        if (Auth::attempt($credentials) && $profile_type === 'App\Models\Aluno') {

            $user = $request->user();

            $user->tokens()->delete();

            $status = AlunoService::verifyStatus($user->profile_id);

            if (!$status) {
                return $this->response('Usuário bloqueado', Response::HTTP_UNAUTHORIZED);
            }

            $response = $this->response('Authorized', Response::HTTP_OK, [
                'token' => $user->createToken('aluno_token', ["aluno"])->plainTextToken
            ]);

            return $response;
        }

        if (Auth::attempt($credentials) && $profile_type === 'App\Models\Professor') {

            $user = $request->user();

            $user->tokens()->delete();

            $response = $this->response('Authorized', Response::HTTP_OK, [
                'token' => $user->createToken('professor_token')->plainTextToken
            ]);

            return $response;
        }

        return $this->error('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Realizar logout no sistema",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna mensagem de token revogado"),
     *     security={
     *          { "apiAuth": {} }
     *     }
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Token Revoked', Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/reset-password",
     *     summary="Redefinir senha do usuário logado",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna mensagem de senha alterada com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *    @OA\RequestBody(
     *        required=true,
     *       @OA\JsonContent(
     *          required={"current_password", "new_password", "new_password_confirmation"},
     *          @OA\Property(property="current_password", type="string", format="password", example="password"),
     *          @OA\Property(property="new_password", type="string", format="password", example="newpassword"),
     *          @OA\Property(property="new_password_confirmation", type="string", format="password", example="newpassword"),
     *          )
     *      )
     * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'min:8'],
            'new_password' => ['required', 'confirmed', 'min:8']
        ]);

        $auth = Auth::user();

        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return $this->response('A senha não corresponde a senha atual', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (strcmp($request->current_password, $request->new_password) == 0) {
            return $this->response("A nova senha não pode ser igual a senha atual. Por favor, escolha uma senha diferente.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->user()->tokens()->delete();

        return $this->response('Senha alterada com sucesso, realize login novamente', Response::HTTP_OK);
    }
}
