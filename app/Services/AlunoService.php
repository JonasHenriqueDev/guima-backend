<?php

namespace App\Services;

use App\Models\Aluno;
use Illuminate\Support\Facades\Log;



class AlunoService
{
    public static function verifyStatus(int $id): bool
    {
        $aluno = Aluno::find($id);

        Log::info("Verifying status for Aluno ID: {$id}, Vencimento: {$aluno->vencimento}, Status: {$aluno->status}");

        if ($aluno->vencimento > now()->subDays(10)) {
            $aluno->status = 1;
        } else {
            $aluno->status = 0;
        }

        Log::info("Updated status for Aluno ID: {$id}, New Status: {$aluno->status}");
        $aluno->save();

        return $aluno->status;
    }
}
