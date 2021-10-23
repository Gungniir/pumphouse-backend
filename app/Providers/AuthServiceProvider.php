<?php

namespace App\Providers;

use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use App\Policies\BillPolicy;
use App\Policies\PeriodPolicy;
use App\Policies\ResidentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Bill::class => BillPolicy::class,
        Resident::class => ResidentPolicy::class,
        Period::class => PeriodPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
