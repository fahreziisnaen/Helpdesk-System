<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $message = "🎫 *TIKET BARU*\n\n";
        $message .= "• *Nomor:* {$ticket->ticket_number}\n";
        $message .= "• *Judul:* {$ticket->title}\n";
        $message .= "• *User:* {$ticket->user->name}" . ($ticket->user->perusahaan ? " ({$ticket->user->perusahaan})" : "") . "\n";
        $message .= "• *Kategori:* " . ($ticket->categoryModel ? $ticket->categoryModel->name : 'N/A') . "\n";
        $message .= "• *Prioritas:* " . strtoupper($ticket->priority) . "\n";
        $message .= "• *Deskripsi:* {$ticket->description}\n\n";
        $message .= "_Dikirim otomatis oleh Sistem Helpdesk_";

        // Send to Group
        $this->whatsapp->sendMessage($message);

        // Send confirmation to Customer
        if ($ticket->user->phone) {
            $customerMsg = "Halo {$ticket->user->name}, tiket Anda #{$ticket->ticket_number} telah kami terima. Teknisi kami akan segera memprosesnya.\n\n" . $message;
            $this->whatsapp->sendMessage($customerMsg, $ticket->user->phone);
        }
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        // Only notify if status or technician assignment changed
        if ($ticket->wasChanged('status') || $ticket->wasChanged('assigned_to')) {
            $message = "📝 *UPDATE TIKET #{$ticket->ticket_number}*\n\n";
            $message .= "• *Judul:* {$ticket->title}\n";
            $message .= "• *Status:* " . strtoupper($ticket->status) . "\n";
            $message .= "• *Teknisi:* " . ($ticket->assignedTechnician ? $ticket->assignedTechnician->name : 'Belum Ditugaskan') . "\n\n";
            $message .= "Silakan cek dashboard untuk update selengkapnya.";

            // Send to Group
            $this->whatsapp->sendMessage($message);

            // Send to Customer
            if ($ticket->user->phone) {
                $this->whatsapp->sendMessage($message, $ticket->user->phone);
            }

            // If a technician was just assigned, notify them personally
            if ($ticket->wasChanged('assigned_to') && $ticket->assignedTechnician && $ticket->assignedTechnician->phone) {
                $techMsg = "Halo {$ticket->assignedTechnician->name}, Anda telah ditugaskan untuk menangani tiket baru:\n\n" . $message;
                $this->whatsapp->sendMessage($techMsg, $ticket->assignedTechnician->phone);
            }
        }
    }
}
