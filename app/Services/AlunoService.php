<?php

namespace App\Services;

use App\Models\Aluno;


class AlunoService
{
    public static function verifyStatus(int $id): bool
    {
        $aluno = Aluno::find($id);

        if ($aluno->vencimento < now()->addDays(10)) {
            $aluno->status = false;
        } else {
            $aluno->status = true;
        }

        $aluno->save();

        return $aluno->status;
    }
}
