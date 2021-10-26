<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PumpMeterRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
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

    public function test_view(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($admin)->getJson("/api/pump-meter-records/{$record->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume
            ],
        ]);
    }

    public function test_view_as_resident(): void
    {
        $admin = User::factory()
            ->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($admin)->getJson("/api/pump-meter-records/{$record->id}");

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();
        $record2 = PumpMeterRecord::factory()
            ->for(Period::factory()->fromDate(2021, 6))
            ->create();

        $response = $this->actingAs($admin)->getJson("/api/pump-meter-records");

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

    public function test_index_as_resident(): void
    {
        $admin = User::factory()
            ->make();

        PumpMeterRecord::factory()->create();
        PumpMeterRecord::factory()
            ->for(Period::factory()->fromDate(2021, 6))
            ->create();

        $response = $this->actingAs($admin)->getJson("/api/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_index_period(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($admin)->getJson("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume
            ],
        ]);
    }

    public function test_index_period_as_resident(): void
    {
        $admin = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($admin)->getJson("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_update_and_view_as_admin(): void
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($user)->patchJson("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}", [
            'amount_volume' => $record->amount_volume * 2,
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->getJson("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $record->id,
                'period_id' => $record->period_id,
                'amount_volume' => $record->amount_volume * 2
            ],
        ]);
    }

    public function test_update_and_view_as_resident(): void
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}", [
            'amount_volume' => $record->amount_volume * 2,
        ]);

        $response->assertForbidden();

        $response = $this->actingAs($user)->getJson("/api/periods/{$record->period_id}/pump-meter-records");

        $response->assertForbidden();
    }

    public function test_insert(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->make();

        $response = $this->actingAs($admin)->postJson("/api/periods/{$record->period_id}/pump-meter-records", [
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

    public function test_insert_as_resident(): void
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->make();

        $response = $this->actingAs($user)->postJson("/api/periods/{$record->period_id}/pump-meter-records", [
            'amount_volume' => $record->amount_volume
        ]);

        $response->assertForbidden();
    }

    public function test_delete(): void
    {
        $admin = User::factory()
            ->state(['login' => config('admin.login')])
            ->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($admin)->deleteJson("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}");

        $response->assertOk();

        $this->assertDatabaseCount('pump_meter_records', 0);
    }

    public function test_delete_as_resident(): void
    {
        $user = User::factory()->make();

        $record = PumpMeterRecord::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/periods/{$record->period_id}/pump-meter-records/{$record->id}");

        $response->assertForbidden();
    }
}
