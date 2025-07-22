<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebDesign;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebDesignPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, WebDesign $webDesign)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $users = $user->findOrfail(auth()->user()->id);

        return $users->roles->where('id',3)->first();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, WebDesign $webDesign)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, WebDesign $webDesign)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, WebDesign $webDesign)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WebDesign  $webDesign
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, WebDesign $webDesign)
    {
        //
    }
}
