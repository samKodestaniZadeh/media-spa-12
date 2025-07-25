<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Financial;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user,Financial $payment)
    {
        return $payment->id  && $payment->user_id == auth()->user()->id  ?  true :  abort(404);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financial  $financial
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Financial $financial)
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
     * @param  \App\Models\Financial  $financial
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Financial $financial)
    {
        $users = $user->findOrfail(auth()->user()->id);

        return $users->roles->where('id',4)->first();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financial  $financial
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Financial $financial)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financial  $financial
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Financial $financial)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Financial  $financial
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Financial $financial)
    {
        //
    }
}
