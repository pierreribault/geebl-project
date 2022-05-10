<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Seller $seller)
    {
        return $user->seller->company == $seller->company;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Seller $seller)
    {
        return $user->seller->isOwner() && $user->seller->company == $seller->company;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Seller $seller)
    {
        return $user->seller->isOwner() && $user->seller->company == $seller->company;
    }
}
