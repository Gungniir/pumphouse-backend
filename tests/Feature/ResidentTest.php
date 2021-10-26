<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_me(): void
    {
        $resident = Resident::factory()->create();
        $user = User::factory()->for($resident)->create();

        $response = $this->actingAs($user)->getJson('/api/residents/me');

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $resident->id,
                'fio' => $resident->fio,
                'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
            ],
        ]);
    }
    public function test_get_me_as_not_a_resident(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/residents/me');

        $this->assertEquals($response->content(), '"You are not a resident"');

        $response->assertNotFound();
    }

    public function test_store(): void
    {
        $admin = User::make([
            'login' => config('admin.login'),
        ]);

        $resident = Resident::factory()->make();

        $response = $this->actingAs($admin)->postJson('/api/residents', [
            'fio' => $resident->fio,
            'area' => $resident->area,
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ]);

        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'fio',
                'area',
                'start_date',
            ],
        ]);
        $response->assertJson([
            'data' => [
                'fio' => $resident->fio,
                'area' => $resident->area,
                'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
            ],
        ]);
    }

    public function test_store_as_resident(): void
    {
        $user = User::factory()->make();

        $resident = Resident::factory()->make();

        $response = $this->actingAs($user)->postJson('/api/residents', [
            'fio' => $resident->fio,
            'area' => $resident->area,
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ]);

        $response->assertForbidden();
    }

    public function test_update_and_show(): void
    {
        $admin = User::make([
            'login' => config('admin.login'),
        ]);

        $resident = Resident::factory()->create();

        $response = $this->actingAs($admin)->putJson("/api/residents/{$resident->id}", [
            'fio' => $resident->fio . ' Updated',
            'area' => number_format($resident->area * 10, 2),
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ]);

        $response->assertOk();

        $response = $this->actingAs($admin)->get("/api/residents/{$resident->id}");

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $resident->id,
                'fio' => $resident->fio . ' Updated',
                'area' => number_format($resident->area * 10, 2),
                'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
            ],
        ]);
    }

    public function test_update_as_resident(): void
    {
        $user = User::factory()->make();

        $resident = Resident::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/residents/{$resident->id}", [
            'fio' => $resident->fio . ' Updated',
            'area' => number_format($resident->area * 10, 2),
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ]);

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        $admin = User::make([
            'login' => config('admin.login'),
        ]);

        $resident = Resident::factory()->create();

        $response = $this->actingAs($admin)->deleteJson("/api/residents/{$resident->id}");

        $response->assertOk();

        $response = $this->actingAs($admin)->getJson("/api/residents/{$resident->id}");

        $response->assertNotFound();
    }

    public function test_destroy_as_resident(): void
    {
        $user = User::factory()->make();

        $resident = Resident::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/residents/{$resident->id}");

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        $admin = User::make([
            'login' => config('admin.login'),
        ]);

        Resident::factory()->count(50)->create();

        $response = $this->actingAs($admin)->getJson("/api/residents");

        $response->assertOk();

        $response->assertJsonCount(50, 'data');
    }

    public function test_index_as_resident(): void
    {
        $user = User::factory()->make();

        Resident::factory()->count(50)->create();

        $response = $this->actingAs($user)->getJson("/api/residents");

        $response->assertForbidden();
    }
}
