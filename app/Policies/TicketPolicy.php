<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view tickets
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Use case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        
        // Admin can view all tickets
        if ($role === 'admin') {
            return true;
        }

        // Teknisi can view assigned tickets or tickets they created
        if ($role === 'teknisi') {
            // Use type casting for ID comparison to handle string/int differences
            return (int)$ticket->assigned_to === (int)$user->id || (int)$ticket->user_id === (int)$user->id;
        }

        // User can only view their own tickets
        // Use type casting for ID comparison to handle string/int differences
        return (int)$ticket->user_id === (int)$user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only users can create tickets
        // Use case-insensitive check to handle shared hosting issues
        $role = strtolower(trim($user->role ?? ''));
        return $role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Use case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        
        // Admin can update all tickets
        if ($role === 'admin') {
            return true;
        }

        // Teknisi can update assigned tickets
        if ($role === 'teknisi') {
            // Use type casting for ID comparison to handle string/int differences
            return (int)$ticket->assigned_to === (int)$user->id;
        }

        // User can update their own tickets if status is open
        // Use type casting for ID comparison to handle string/int differences
        return (int)$ticket->user_id === (int)$user->id && $ticket->status === 'open';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Only admin can delete tickets
        // Use case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        return $role === 'admin';
    }

    /**
     * Determine whether the user can assign tickets.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        // Only admin can assign tickets
        // Use case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        return $role === 'admin';
    }

    /**
     * Determine whether the user can change ticket status.
     */
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        // Use case-insensitive check for shared hosting compatibility
        $role = strtolower(trim($user->role ?? ''));
        
        // Admin can change status of all tickets
        if ($role === 'admin') {
            return true;
        }

        // Teknisi can change status of assigned tickets
        if ($role === 'teknisi') {
            // Use type casting for ID comparison to handle string/int differences
            return (int)$ticket->assigned_to === (int)$user->id;
        }

        return false;
    }
}
