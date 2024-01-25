<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserStoreUpdateFormRequest;
use App\Models\Aluno;
use App\Models\Professor;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected User $repository,
    ) {
        $this->middleware('auth:sanctum')->except(['store']);
    }

    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    public function store(UserStoreUpdateFormRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $profileType = $data['profile_type'];

        $allowedProfileTypes = ['professor', 'aluno'];

        if (!in_array($profileType, $allowedProfileTypes)) {
            return $this->error('Tipo de perfil não reconhecido', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        switch ($profileType) {
            case 'professor':
                $professor = Professor::create();
                $user = $professor->user()->create($data);
                break;
            case 'aluno':
                $aluno = Aluno::create();
                $user = $aluno->user()->create($data);
                break;
            default:
                return $this->error('Tipo de perfil não reconhecido', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UserResource($user);
    }


    public function show($id)
    {
        $user = $this->repository->findOrFail($id);

        return new UserResource($user);
    }

    public function update(UserStoreUpdateFormRequest $request, string $id)
    {
        $user = $this->repository->findOrFail($id);

        $data = $request->validated();

        if ($request->password) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = $this->repository->findOrFail($id);
        $user->delete();

        return $this->response('User deleted', Response::HTTP_NO_CONTENT);
    }
}
