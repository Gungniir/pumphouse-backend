<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeriodPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @return void|bool
     */
    public function before(User $user)
    {
        if ($user->login === config('admin.login')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @return bool
     */
    public function viewAny(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return bool
     */
    public function view(): bool
    {
        return false;
    }
}
