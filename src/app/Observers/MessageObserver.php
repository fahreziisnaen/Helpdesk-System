<?php

namespace App\Observers;

use App\Models\Message;
use App\Services\WhatsAppService;

class MessageObserver
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        $ticket = $message->ticket;
        $user = $message->user;

        $waMessage = "💬 *PESAN BARU PADA TIKET #{$ticket->ticket_number}*\n\n";
        $waMessage .= "• *Dari:* {$user->name} (" . ucfirst($user->role) . ")\n";
        $waMessage .= "• *Isi Pesan:* {$message->message}\n\n";
        $waMessage .= "_Gunakan dashboard untuk membalas pesan ini._";

        $this->whatsapp->sendMessage($waMessage);
    }
}
