<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @param string $ability
     * @return void|bool
     */
    public function before(User $user, string $ability)
    {
        if ($user->login === config('admin.login')) {
            return true;
        }
    }

    public function view(User $user, Bill $bill)
    {
        if (is_null($user->resident)) {
            return Response::deny("You are not a resident");
        }

        return $user->resident->id === $bill->resident_id;
    }
}
