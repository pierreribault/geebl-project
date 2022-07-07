<?php

namespace App\Policies;

use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isProfesionnal();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketCategory  $ticketCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TicketCategory $ticketCategory)
    {
        return $user->isInCompany($ticketCategory->event->author->company);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isRedactor() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketCategory  $ticketCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TicketCategory $ticketCategory)
    {
        if (!$user->isInCompany($ticketCategory->event->author->company)) {
            return false;
        }

        return $user->isReviewer() || $user->isRedactor() || $user->isOwner();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketCategory  $ticketCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TicketCategory $ticketCategory)
    {
        if (!$user->isInCompany($ticketCategory->event->author->company)) {
            return false;
        }

        return $user->isOwner();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketCategory  $ticketCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TicketCategory $ticketCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketCategory  $ticketCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TicketCategory $ticketCategory)
    {
        //
    }
}
