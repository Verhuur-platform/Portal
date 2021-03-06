<?php

namespace App\Policies;

use App\User;
use App\Models\Helpdesk;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class HelpdeskPolicy 
 * 
 * @package App\Policies
 */
class HelpdeskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the helpdesk ticket or not. 
     *
     * @param  User     $user   De databank entity van de aangemelde gebruiker. 
     * @param  Helpdesk $ticket De databank entity van het helpdesk ticket.  
     * @return bool
     */
    public function viewTicket(User $user, Helpdesk $ticket): bool 
    {
        return $user->id === $ticket->created_by || $user->hasRole('admin');
    }

    /**
     * Determine wheter the authenticated user can assign himself to the ticket.
     *
     * @param  User     $user   De databank entiteit van de aangemelde gebruiker.
     * @param  Helpdesk $ticket De databank entiteit van het helpdesk ticket.
     * @return bool
     */
    public function assignTicket(User $user, Helpdesk $ticket): bool
    {
        return $user->hasRole('admin') && ! $ticket->assigned && $user->id !== $ticket->created_by;
    }

    /**
     * Determine wheter the authenticated user can close the ticket or not. 
     * 
     * @param  User     $user   De databank entiteit van de aangemelde gebruiker. 
     * @param  Helpdesk $ticket De databank entity van het helpdesk tikcet.  
     * @return bool 
     */
    public function closeTicket(User $user, Helpdesk $ticket): bool 
    {
        return $ticket->created_by === $user->id || $ticket->assigned === $user->id;
    }

    /**
     * Determine whether the user can edit the ticket or not.
     *
     * @param  User     $user   De databank entiteit van de aangemelde gebruiker.
     * @param  Helpdesk $ticket De databank entiteit van het helpdesk ticket.
     * @return bool
     */
    public function edit(User $user, Helpdesk $ticket): bool
    {
        return $user->id === $ticket->created_by;
    }

    /**
     * Determine whether the authenticated user can create an helpdesk ticket or not. 
     * 
     * @param  User $user The database entity from the authenticated user. 
     * @return bool
     */
    public function store(User $user): bool 
    {
        return $user->hasAnyRole(['admin', 'huurder']);
    }
}
