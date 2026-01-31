<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Store a newly created message
     */
    public function store(StoreMessageRequest $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);

        // Check authorization with case-insensitive check for shared hosting compatibility
        $user = Auth::user();
        if (!$user) {
            abort(403, 'This action is unauthorized.');
        }
        
        $role = strtolower(trim($user->role ?? ''));
        $canMessage = false;

        // Admin can message on all tickets
        if ($role === 'admin') {
            $canMessage = true;
        } 
        // Teknisi can message on assigned tickets or tickets they created
        elseif ($role === 'teknisi') {
            // Use type casting for ID comparison to handle string/int differences
            $canMessage = (int)$ticket->assigned_to === (int)$user->id || (int)$ticket->user_id === (int)$user->id;
        } 
        // User can only message on their own tickets
        else {
            // Use type casting for ID comparison to handle string/int differences
            $canMessage = (int)$ticket->user_id === (int)$user->id;
        }

        if (!$canMessage) {
            abort(403, 'This action is unauthorized.');
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;

        // Handle attachment
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('messages', 'public');
        }

        $message = Message::create($data);

        // Notify other participants
        $notifyUsers = [];

        // Use case-insensitive role check for shared hosting compatibility
        if ($role === 'admin' || $role === 'teknisi') {
            // Notify ticket creator
            if ((int)$ticket->user_id !== (int)$user->id) {
                $notifyUsers[] = $ticket->user_id;
            }
            // Notify assigned teknisi if admin sent message
            if ($role === 'admin' && $ticket->assigned_to && (int)$ticket->assigned_to !== (int)$user->id) {
                $notifyUsers[] = $ticket->assigned_to;
            }
        } else {
            // User sent message, notify admin and assigned teknisi
            if ($ticket->assigned_to) {
                $notifyUsers[] = $ticket->assigned_to;
            }
            // Notify all admins (use case-insensitive check)
            $admins = \App\Models\User::whereRaw('LOWER(TRIM(role)) = ?', ['admin'])->pluck('id');
            $notifyUsers = array_merge($notifyUsers, $admins->toArray());
        }

        foreach (array_unique($notifyUsers) as $userId) {
            Notification::create([
                'user_id' => $userId,
                'ticket_id' => $ticket->id,
                'type' => 'message_received',
                'title' => 'Pesan Baru',
                'message' => "Pesan baru pada tiket: {$ticket->title}",
            ]);
        }

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'This action is unauthorized.');
        }
        
        // Use type casting for ID comparison to handle string/int differences
        if ((int)$message->user_id !== (int)$user->id) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get messages for a ticket
     */
    public function getMessages(Ticket $ticket)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'This action is unauthorized.');
        }

        // Check authorization with case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        $canView = false;
        
        // Admin can view messages on all tickets
        if ($role === 'admin') {
            $canView = true;
        } 
        // Teknisi can view messages on assigned tickets or tickets they created
        elseif ($role === 'teknisi') {
            // Use type casting for ID comparison to handle string/int differences
            $canView = (int)$ticket->assigned_to === (int)$user->id || (int)$ticket->user_id === (int)$user->id;
        } 
        // User can only view messages on their own tickets
        else {
            // Use type casting for ID comparison to handle string/int differences
            $canView = (int)$ticket->user_id === (int)$user->id;
        }

        if (!$canView) {
            abort(403, 'This action is unauthorized.');
        }

        $messages = $ticket->messages()
            ->with('user')
            ->latest()
            ->get();

        return response()->json($messages);
    }
}
