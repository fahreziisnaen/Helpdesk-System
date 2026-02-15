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

        $senderInfo = "{$user->name}";
        if ($user->perusahaan) {
            $senderInfo .= " ({$user->perusahaan})";
        }

        $waMessage = "💬 *PESAN BARU PADA TIKET #{$ticket->ticket_number}*\n\n";
        $waMessage .= "• *Dari:* {$senderInfo} [" . ucfirst($user->role) . "]\n";
        $waMessage .= "• *Isi Pesan:* {$message->message}\n\n";
        $waMessage .= "_Gunakan dashboard untuk membalas pesan ini._";

        // Always send to Group
        $this->whatsapp->sendMessage($waMessage);

        // Determine personal recipient
        if ($user->role === 'user') {
            // Customer sent message -> Notify assigned technician if exists
            if ($ticket->assignedTechnician && $ticket->assignedTechnician->phone) {
                $this->whatsapp->sendMessage($waMessage, $ticket->assignedTechnician->phone);
            }
        } else {
            // Staff (Admin/Teknisi) sent message -> Notify Customer
            if ($ticket->user->phone) {
                $this->whatsapp->sendMessage($waMessage, $ticket->user->phone);
            }
        }
    }
}
