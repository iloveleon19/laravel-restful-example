<?php

namespace App\Policies;

use App\User;
use App\Animal;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if($user->permission=='admin'){
            return true;
        }
    }
    
    /**
     * Determine whether the user can view any animals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the animal.
     *
     * @param  \App\User  $user
     * @param  \App\Animal  $animal
     * @return mixed
     */
    public function view(User $user, Animal $animal)
    {
        //
    }

    /**
     * Determine whether the user can create animals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the animal.
     *
     * @param  \App\User  $user
     * @param  \App\Animal  $animal
     * @return mixed
     */
    public function update(User $user, Animal $animal)
    {
        if($user->id===$animal->user_id){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the animal.
     *
     * @param  \App\User  $user
     * @param  \App\Animal  $animal
     * @return mixed
     */
    public function delete(User $user, Animal $animal)
    {
        if($user->id===$animal->user_id){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the animal.
     *
     * @param  \App\User  $user
     * @param  \App\Animal  $animal
     * @return mixed
     */
    public function restore(User $user, Animal $animal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the animal.
     *
     * @param  \App\User  $user
     * @param  \App\Animal  $animal
     * @return mixed
     */
    public function forceDelete(User $user, Animal $animal)
    {
        //
    }
}
