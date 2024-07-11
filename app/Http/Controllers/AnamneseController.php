<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnamneseRequest;
use App\Http\Requests\UpdateAnamneseRequest;
use App\Http\Resources\AnamneseResource;
use App\Models\Aluno;
use App\Models\Anamnese;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AnamneseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AnamneseResource::collection(Anamnese::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnamneseRequest $request)
    {
        //dd($request->all());
        $data = $request->validated();
        $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

        try {

            if (Anamnese::where('cpf', $data['cpf'])->exists() || User::where('cpf', $data['cpf'])->exists() || User::where('cpf', $request->cpf)->exists()) {
                return $this->error('Já existe uma anamnese para este CPF', 400);
            }
            $anamnese = Anamnese::create($data);

            return AnamneseResource::make($anamnese);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $cpf)
    {
        try {
            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            $anamnese = Anamnese::where('cpf', $cpf)->firstOrFail();
            return AnamneseResource::make($anamnese);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return $this->error("Anamnese não encontrada para o CPF: $cpf", 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnamneseRequest $request, string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        try {
            $anamnese = Anamnese::where('cpf', $cpf)->firstOrFail();
            $data = $request->validated();
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

            if (User::where('cpf', $data['cpf'])->exists() || User::where('cpf', $request->cpf)->exists()) {
                return $this->error('Já existe uma anamnese para este CPF', 400);
            }

            $anamnese->update($data);
            return AnamneseResource::make($anamnese);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            return $this->error("Anamnese não encontrada para o CPF: $cpf", 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anamnese $anamnese)
    {
        //
    }

    public function aprovarAnamnese(string $id)
    {
        $anamnese = Anamnese::where('id', $id)->firstOrFail();

        if ($anamnese->is_aprovada) {
            return $this->error('Anamnese já foi aprovada', 400);
        }


        $alunoData = Arr::only($anamnese->toArray(), ['plano', 'vencimento', 'status']);
        $alunoData['status'] = true;
        $alunoData['anamnese_id'] = $anamnese->id;
        
        $aluno = Aluno::create($alunoData);

        $user = $aluno->user()->create($anamnese->toArray() + ['password' => Hash::make($anamnese->cpf)] + ['cpf' => $anamnese->cpf]);

        $anamnese->update(['is_aprovada' => true]);
        $anamnese->save();
        return $this->response('Anamnese aprovada com sucesso', Response::HTTP_OK);
    }
}
