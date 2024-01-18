<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\UserStoreFormRequest;

class UserController extends Controller
{
    public function __construct(
        protected User $repository,
    ) {

    }

    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    public function store(UserStoreFormRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = $this->repository->create($data);

        return new UserResource($user);
    }
}
