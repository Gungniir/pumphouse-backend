<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Resident;
use App\Models\User;
use Config;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PumpMeterRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_create()
    {
        PumpMeterRecord::factory()->create();

        $now = new DateTime();
        $previousMonth = (new DateTime())->setDate(
            (int)$now->format('Y'),
            (int)$now->format('m') - 2,
            1,
        );

        PumpMeterRecord::factory()
            ->for(
                Period::factory()
                    ->state([
                        'begin_date' => $previousMonth->format('Y.m.1 0:0:0'),
                        'end_date' => $previousMonth->format('Y.m.t 0:0:0'),
                    ])
            )
            ->create();

        $this->assertDatabaseCount('pump_meter_records', 2);
    }

    public function test_view()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/pump-meter-records/{$record->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume
            ],
        ]);
    }

    public function test_view_as_resident()
    {
        $admin = User::factory()
            ->make();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/pump-meter-records/{$record->id}");

        $response->assertForbidden();
    }

    public function test_index()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();
        $record2 = PumpMeterRecord::factory()
            ->for(Period::factory()->fromDate(2021, 6))
            ->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/pump-meter-records");

        $response->assertOk();

        $response->assertJsonCount(2, 'data');

        $response->assertJson([
            'data' => [
                [
                    'id' => $record->id,
                    'period_id' => $record->period_id,
                    'amount_volume' => $record->amount_volume
                ],
                [
                    'id' => $record2->id,
                    'period_id' => $record2->period_id,
                    'amount_volume' => $record2->amount_volume
                ],
            ],
        ]);
    }

    public function test_index_as_resident()
    {
        $admin = User::factory()
            ->make();

        PumpMeterRecord::factory()->create();
        PumpMeterRecord::factory()
            ->for(Period::factory()->fromDate(2021, 6))
            ->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_index_period()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume
            ],
        ]);
    }

    public function test_index_period_as_resident()
    {
        $admin = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->get("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_update_and_view_as_admin()
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($user)->patchJson("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}", [
            'amount_volume' => $record->amount_volume * 2,
        ]);

        $response->assertOk();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($user)->get("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume * 2
            ],
        ]);
    }

    public function test_update_and_view_as_resident()
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($user)->put("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}", [
            'amount_volume' => $record->amount_volume * 2,
        ]);

        $response->assertForbidden();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($user)->get("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_insert()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->make();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->post("/api/periods/{$record->period_id}/pump-meter-records", [
            'amount_volume' => $record->amount_volume
        ]);

        $response->assertCreated();

        $record = PumpMeterRecord::wherePeriodId($record->period_id)->first();

        $this->assertNotNull($record);

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'amount_volume' => $record->amount_volume,
                'period_id' => $record->period_id,
            ]
        ]);
    }

    public function test_insert_as_resident()
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->make();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($user)->post("/api/periods/{$record->period_id}/pump-meter-records", [
            'amount_volume' => $record->amount_volume
        ]);

        $response->assertForbidden();
    }

    public function test_delete()
    {
        $admin = User::factory()
            ->state(['login' => Config::get('admin.login')])
            ->create();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($admin)->delete("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}");

        $response->assertOk();

        $this->assertDatabaseCount('pump_meter_records', 0);
    }

    public function test_delete_as_resident()
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        /** @noinspection PhpParamsInspection */
        $response = $this->actingAs($user)->delete("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}");

        $response->assertForbidden();
    }
}
