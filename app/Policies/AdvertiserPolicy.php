<?php

namespace App\Policies;

use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertiserPolicy
{
    use HandlesAuthorization;

    protected function isOwner(User $user, Advertiser $advertiser)
    {
        return $user->id === $advertiser->user->id;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Advertiser  $advertiser
     * @return mixed
     */
    public function view(User $user, Advertiser $advertiser)
    {
        return $this->isOwner($user, $advertiser);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Advertiser  $advertiser
     * @return mixed
     */
    public function update(User $user, Advertiser $advertiser)
    {
        return $this->isOwner($user, $advertiser);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Advertiser  $advertiser
     * @return mixed
     */
    public function delete(User $user, Advertiser $advertiser)
    {
        return $this->isOwner($user, $advertiser);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Advertiser  $advertiser
     * @return mixed
     */
    public function restore(User $user, Advertiser $advertiser)
    {
        return $this->isOwner($user, $advertiser);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Advertiser  $advertiser
     * @return mixed
     */
    public function forceDelete(User $user, Advertiser $advertiser)
    {
        return $this->isOwner($user, $advertiser);
    }
}
