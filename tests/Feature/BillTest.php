<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
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

    public function test_index(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->create();

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

        $response = $this->actingAs($admin)->getJson('/api/bills');

        $response->assertOk();

        $response->assertJsonCount(100);
    }

    public function test_index_as_resident(): void
    {
        $user = User::factory()
            ->create();

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

        $response = $this->actingAs($user)->getJson('/api/bills');

        $response->assertForbidden();
    }

    public function test_index_period(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

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

        $response = $this->actingAs($admin)->getJson("/api/periods/$period->id/bills");

        $response->assertOk();

        $response->assertJsonCount(50, 'data');
    }

    public function test_index_period_as_resident(): void
    {
        $admin = User::factory()
            ->make();

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

        $response = $this->actingAs($admin)->getJson("/api/periods/$period->id/bills");

        $response->assertForbidden();
    }

    public function test_view_as_resident_owner(): void
    {
        $resident = Resident::factory()
            ->has(User::factory())
            ->create();

        $bill = Bill::factory()
            ->for($resident)
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($resident->user)->getJson("/api/bills/$bill->id");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);
    }

    public function test_view_as_resident_guest(): void
    {
        $resident = Resident::factory()
            ->has(User::factory())
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($resident->user)->getJson("/api/bills/$bill->id");

        $response->assertForbidden();
    }

    public function test_view_as_admin(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($admin)->getJson("/api/bills/$bill->id");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub
            ]
        ]);
    }

    public function test_update(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($admin)->putJson("/api/bills/$bill->id", [
            'amount_rub' => $bill->amount_rub * 2
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $bill->id,
                'resident_id' => $bill->resident_id,
                'period_id' => $bill->period_id,
                'amount_rub' => $bill->amount_rub * 2
            ]
        ]);
    }

    public function test_update_as_resident(): void
    {
        $user = User::factory()
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->putJson("/api/bills/$bill->id", [
            'amount_rub' => $bill->amount_rub * 2
        ]);

        $response->assertForbidden();
    }

    public function test_insert(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->make();

        $response = $this->actingAs($admin)->postJson("/api/periods/$bill->period_id/bills", [
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

        $response = $this->actingAs($admin)->getJson("/api/bills/$bill->id");

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

    public function test_insert_as_resident(): void
    {
        $user = User::factory()
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->make();

        $response = $this->actingAs($user)->postJson("/api/periods/$bill->period_id/bills", [
            'resident_id' => $bill->resident_id,
            'amount_rub' => $bill->amount_rub
        ]);

        $response->assertForbidden();
    }

    public function test_delete(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($admin)->deleteJson("/api/bills/$bill->id");

        $response->assertOk();

        $this->assertDatabaseCount('bills', 0);
    }

    public function test_delete_as_resident(): void
    {
        $user = User::factory()
            ->create();

        $bill = Bill::factory()
            ->for(Resident::factory())
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->deleteJson("/api/bills/$bill->id");

        $response->assertForbidden();
    }
}
