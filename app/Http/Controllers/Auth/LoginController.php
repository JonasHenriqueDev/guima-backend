<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'min:8'],
            'new_password' => ['required', 'confirmed', 'min:8']
        ]);

        $auth = Auth::user();

        if (!Hash::check($request->get('current_password'), $auth->password)) 
        {
            return $this->response('A senha não corresponde a senha atual', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        if (strcmp($request->current_password, $request->new_password) == 0) 
        {
            return $this->response("A nova senha não pode ser igual a senha atual. Por favor, escolha uma senha diferente.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->user()->tokens()->delete();

        return $this->response('Senha alterada com sucesso, realize login novamente', Response::HTTP_OK);
    }
}
