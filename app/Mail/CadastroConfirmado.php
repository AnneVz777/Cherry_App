<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CadastroConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $nomeUsuario) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cadastro Confirmado!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cadastro-confirmado',
        );
    }
}
