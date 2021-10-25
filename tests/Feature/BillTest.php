<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use App\Models\User;
use Config;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillTest extends TestCase
{
    use RefreshDatabase;

    public function test_create()
    {
        $period = Period::factory()->create();

        $now = new DateTime();
        $previousMonth = (new DateTime())->setDate(
            (int)$now->format('Y'),
            (int)$now->format('m') - 2,
            1,
        );
        $previousPeriod = Period::factory()->state([
            'begin_date' => $previousMonth->format('Y.m.1 0:0:0'),
            'end_date' => $previousMonth->format('Y.m.t 0:0:0'),
        ])->create();

        Resident::factory()
            ->has(
                Bill::factory()
                    ->for($period)
            )
            ->has(
                Bill::factory()
                    ->for($previousPeriod)
            )
            ->count(50)->create();

        $this->assertDatabaseCount('bills', 100);
    }

    public function test_view_as_resident()
    {
        $resident = Resident::factory()
            ->has(User::factory())
            ->create();

        $bill = Bill::factory()
            ->for($resident)
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($resident->user)->get("/api/bills/{$bill->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);
    }

    public function test_view_as_admin()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/bills/{$bill->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);
    }

    public function test_update_and_view()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->put("/api/bills/{$bill->id}", [
            'amount_rub' => $bill->amount_rub * 2
        ]);

        $response->assertOk();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/bills/{$bill->id}");

        $response->assertJson([
            'data' => [
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub * 2
            ]
        ]);
    }

    public function test_insert()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->make();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->post("/api/periods/{$bill->period_id}/bills", [
            'resident_id' => $bill->resident_id,
            'amount_rub' => $bill->amount_rub
        ]);

        $bill = Bill::wherePeriodId($bill->period_id)->whereResidentId($bill->resident_id)->first();

        $this->assertNotNull($bill);

        $response->assertJson([
            'data' => [
                'id' => $bill->id,
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);

        $response->assertCreated();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/bills/{$bill->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $bill->id,
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);
    }

    public function test_delete()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->delete("/api/bills/{$bill->id}");

        $response->assertOk();

        $this->assertDatabaseCount('bills', 0);
    }
}
