<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
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
     * Determine whether the user can view all models.
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
     * @param User $user
     * @param Bill $bill
     * @return bool
     */
    public function view(User $user, Bill $bill): bool
    {
        return $user->resident_id === $bill->resident_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return bool
     */
    public function create(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return bool
     */
    public function restore(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return bool
     */
    public function forceDelete(): bool
    {
        return false;
    }
}
