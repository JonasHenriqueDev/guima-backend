<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnamneseReprovadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anamnese;

    /**
     * Create a new message instance.
     */
    public function __construct($anamnese)
    {
        $this->anamnese = $anamnese;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Anamnese Reprovada')
            ->view('emails.anamnese-reprovada')
            ->with([
                'name' => $this->anamnese->name,
                'motivo_reprovacao' => $this->anamnese->motivo_reprovacao,
            ]);
    }
}
