<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TariffTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->getJson("/api/periods/$tariff->period_id/tariffs");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $tariff->id,
                'period_id' => $tariff->period_id,
                'cost' => $tariff->cost,
            ]
        ]);
    }

    public function test_index_as_resident(): void
    {
        $user = User::factory()->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->getJson("/api/periods/$tariff->period_id/tariffs");

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $tariff->id,
                'period_id' => $tariff->period_id,
                'cost' => $tariff->cost,
            ]
        ]);
    }

    public function test_update(): void
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $newCost = $tariff->cost * 2;

        $response = $this->actingAs($user)->putJson("/api/tariffs/$tariff->id", [
            'cost' => $newCost
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $tariff->id,
                'period_id' => $tariff->period_id,
                'cost' => $tariff->cost * 2,
            ]
        ]);
    }

    public function test_update_as_resident(): void
    {
        $user = User::factory()->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->putJson("/api/tariffs/$tariff->id", [
            'cost' => $tariff->cost * 2
        ]);

        $response->assertForbidden();
    }

    public function test_insert(): void
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->make();

        $response = $this->actingAs($user)->postJson("/api/periods/$tariff->period_id/tariffs", [
            'cost' => $tariff->cost
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('tariffs', 1);
    }

    public function test_insert_as_resident(): void
    {
        $user = User::factory()->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->make();

        $response = $this->actingAs($user)->postJson("/api/periods/$tariff->period_id/tariffs", [
            'cost' => $tariff->cost
        ]);

        $response->assertForbidden();
    }

    public function test_delete(): void
    {
        $user = User::factory()->state([
            'login' => config('admin.login')
        ])->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->deleteJson("/api/tariffs/$tariff->id");

        $response->assertOK();

        $this->assertDatabaseCount('tariffs', 0);
    }

    public function test_delete_as_resident(): void
    {
        $user = User::factory()->make();

        $tariff = Tariff::factory()
            ->for(Period::factory())
            ->create();

        $response = $this->actingAs($user)->deleteJson("/api/tariffs/$tariff->id");

        $response->assertForbidden();
    }
}
