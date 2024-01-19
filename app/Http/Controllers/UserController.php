<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserStoreUpdateFormRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct(
        protected User $repository,
    ) {
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

        $user = $this->repository->create($data);

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

        return $this->response('User deleted', Response::HTTP_OK);
    }
}
