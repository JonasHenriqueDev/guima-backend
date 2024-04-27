<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserStoreUpdateFormRequest;
use App\Models\Aluno;
use App\Models\Professor;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        protected User $repository,
    ) {
        $this->middleware('auth:sanctum')->except(['store']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Listar todos os usuários",
     *     tags={"Usuários"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna a lista de usuários"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     * )
     */
    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    // {
    //     "name":"Adm",
    //     "email":"teste@teste.com",
    //     "birth_date": "01-01-2003",
    //     "cpf": "111.111.111-13",
    //     "address": "teste rua",
    //     "profile_type": "aluno",
    //     "plano": "mensal",
    //     "vencimento": "2023-12-12",
    //     "status": true
    // }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Registrar um novo usuário",
     *     description="A senha padrão é o cpf do usuário.",
     *     tags={"Autenticação"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o usuário registrado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Joao"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(property="birth_date", type="string", example="2003-01-01"),
     *              @OA\Property(property="cpf", type="string", example="111.111.111-13"),
     *              @OA\Property(property="address", type="string", example="Rua teste"),
     *              @OA\Property(property="profile_type", type="string", example="aluno"),
     *              @OA\Property(property="plano", type="string", example="anual"),
     *              @OA\Property(property="vencimento", type="string", example="2025-12-12"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *          )
     *      ),
     * )
     */
    public function store(UserStoreUpdateFormRequest $request)
    {
        $data = $request->validated();
        //$data['password'] = bcrypt($data['password']);
        $profileType = $data['profile_type'];

        $allowedProfileTypes = ['professor', 'aluno'];

        if (!in_array($profileType, $allowedProfileTypes)) {
            return $this->error('Tipo de perfil não reconhecido', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cpf = $data['cpf'];

        switch ($profileType) {
            case 'professor':
                $professor = Professor::create();
                $user = $professor->user()->create($data + ['password' => Hash::make($cpf)]);
                break;
            case 'aluno':
                $alunoData = Arr::only($data, ['plano', 'vencimento', 'status']);
                $aluno = Aluno::create($alunoData);
                $user = $aluno->user()->create($data + ['password' => Hash::make($cpf)]);
                break;
            default:
                return $this->error('Tipo de perfil não reconhecido', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     summary="Mostrar um usuário específico por id",
     *     tags={"Usuários"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o usuário específico por id"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Buscar por id",
     *          required=true,
     *         ) 
     * )
     */
    public function show($id)
    {
        $user = $this->repository->findOrFail($id);

        return new UserResource($user);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/users/{id}",
     *     summary="Atualizar um usuário",
     *     tags={"Usuários"},
     *     description="Para atualizar a senha, utilize o endpoint POST /api/v1/auth/reset-password.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Retorna o usuário atualizado"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Joao"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(property="birth_date", type="string", example="01-01-2003"),
     *              @OA\Property(property="cpf", type="string", example="111.111.111-13"),
     *              @OA\Property(property="address", type="string", example="Rua teste"),
     *              @OA\Property(property="profile_type", type="string", example="aluno"),
     *              @OA\Property(property="plano", type="string", example="anual"),
     *              @OA\Property(property="vencimento", type="string", example="2025-12-12"),
     *              @OA\Property(property="status", type="boolean", example="true"),
     *          )
     *      ),
     * )
     */
    public function update(UserStoreUpdateFormRequest $request, string $id)
    {
        $user = $this->repository->findOrFail($id);

        $data = $request->validated();

        // if ($request->password) {
        //     $data['password'] = Hash::make($data['password']);
        // }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     summary="Deletar um usuário específico por id",
     *     description="Deletar um usuário específico por id",
     *     tags={"Usuários"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="204", description="Usuário deletado com sucesso"),
     *     security={
     *          { "apiAuth": {} }
     *     },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id do usuário",
     *          required=true,
     *         ) 
     * )
     */
    public function destroy($id)
    {
        $user = $this->repository->findOrFail($id);
        $user->delete();

        return $this->response('Usuário deletado com sucesso', Response::HTTP_NO_CONTENT);
    }
}
