<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $email = $request->email;

        $profile_type = User::where('email', $email)->firstOrFail()->profile_type;

        if (Auth::attempt($credentials) && $profile_type === 'App\Models\Aluno') {

            $response = $this->response('Authorized', Response::HTTP_OK, [
                'token' => $request->user()->createToken('aluno_token', ["aluno"])->plainTextToken
            ]);

            return $response;
        }

        if (Auth::attempt($credentials) && $profile_type === 'App\Models\Professor') {

            $response = $this->response('Authorized', Response::HTTP_OK, [
                'token' => $request->user()->createToken('professor_token', ["*", "professor"])->plainTextToken
            ]);

            return $response;
        }

        return $this->error('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response('Token Revoked', Response::HTTP_OK);
    }
}
