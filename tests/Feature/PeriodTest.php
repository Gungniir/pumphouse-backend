<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Period;
use App\Models\PumpMeterRecord;
use App\Models\Resident;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        $now = new DateTime();

        for ($i = 0; $i < 50; $i++) {
            $periodDate = new DateTime();
            $periodDate->setDate(
                $now->format('Y'),
                (int)$now->format('m') - ($i + 1),
                1
            );

            Period::factory()->state([
                'begin_date' => $periodDate->format('Y.m.1 0:0:0'),
                'end_date' => $periodDate->format('Y.m.t 23:59:59'),
            ])->create();
        }

        $this->assertDatabaseCount('periods', 50);
    }

    public function test_insert_as_admin(): void
    {
        $admin = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $response = $this->actingAs($admin)->postJson('/api/periods', [
            'year' => 2021,
            'month' => 10,
        ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'begin_date',
                'end_date',
            ]
        ]);

        $response->assertJson([
            'data' => [
                'begin_date' => '2021.10.01 00:00:00',
                'end_date' => '2021.10.31 23:59:59'
            ]
        ]);

        $this->assertDatabaseCount('periods', 1);
    }

    public function test_insert_as_guest(): void
    {
        $response = $this->postJson('/api/periods', [
            'year' => 2021,
            'month' => 10,
        ]);

        $response->assertUnauthorized();
    }

    public function test_insert_as_resident(): void
    {
        $user = User::make();

        $response = $this->actingAs($user)->postJson('/api/periods', [
            'year' => 2021,
            'month' => 10,
        ]);

        $response->assertForbidden();
    }

    public function test_insert_duplicate(): void
    {
        $admin = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        Period::factory()->fromDate(2021, 10)->create();

        $response = $this->actingAs($admin)->postJson('/api/periods', [
            'year' => 2021,
            'month' => 10,
        ]);

        $response->assertStatus(409);

        $this->assertMatchesRegularExpression('/Duplicate found: \d+/m', $response->content());

        $this->assertDatabaseCount('periods', 1);
    }

    public function test_index_as_admin(): void
    {
        $now = new DateTime();

        for ($i = 0; $i < 50; $i++) {
            $periodDate = new DateTime();
            $periodDate->setDate(
                $now->format('Y'),
                (int)$now->format('m') - ($i + 1),
                1
            );

            Period::factory()->state([
                'begin_date' => $periodDate->format('Y.m.1 0:0:0'),
                'end_date' => $periodDate->format('Y.m.t 23:59:59'),
            ])->create();
        }

        $admin = User::factory()->state([
            'login' => config('admin.login'),
        ])->make();

        $response = $this->actingAs($admin)->getJson('/api/periods');

        $response->assertOk();

        $response->assertJsonStructure([
            'data'
        ]);

        $response->assertJsonCount(50, 'data');
    }

    public function test_index_as_resident(): void
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson('/api/periods');

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        $period = Period::factory()->create();

        $admin = User::factory()->state([
            'login' => config('admin.login'),
        ])->make();

        $response = $this->actingAs($admin)->getJson("/api/periods/{$period->id}");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $period->id,
                'begin_date' => $period->begin_date,
                'end_date' => $period->end_date,
            ]
        ]);
    }

    public function test_show_as_resident(): void
    {
        $period = Period::factory()->create();

        $user = User::factory()->make();

        $response = $this->actingAs($user)->getJson("/api/periods/{$period->id}");

        $response->assertForbidden();
    }

    public function test_calculate(): void
    {
        // Генерация периода
        $period = Period::factory()
            ->fromDate(2021, 9)
            ->has(PumpMeterRecord::factory()) // Сразу генерируем значение счетчика
            ->create();

        // Генарация дачников
        Resident::factory()->count(50)->create();


        $admin = User::factory()->state([
            'login' => config('admin.login'),
        ])->make();

        $response = $this->actingAs($admin)->postJson("/api/periods/{$period->id}/calculate");

        $response->assertOk();

        $sum = Bill::sum('amount_rub');

        // TODO: цену
        $this->assertEquals($sum, $period->pumpMeterRecord->amount_volume*2);
    }

    public function test_calculate_as_resident(): void
    {
        $period = Period::factory()
            ->fromDate(2021, 9)
            ->has(PumpMeterRecord::factory()) // Сразу генерируем значение счетчика
            ->create();

        $user = User::factory()->make();

        $response = $this->actingAs($user)->postJson("/api/periods/{$period->id}/calculate");

        $response->assertForbidden();
    }
}
