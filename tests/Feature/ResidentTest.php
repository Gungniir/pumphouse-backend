<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_store()
    {
        $admin = User::make([
            'login' => 'gungniir',
        ]);

        $resident = Resident::factory()->make();

        $response = $this->actingAs($admin)->post('/api/residents', [
            'fio' => $resident->fio,
            'area' => $resident->area,
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ], [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(201);

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

        $resident->id = $response->json('data.id');

        return [$admin, $resident];
    }

    /**
     * @depends test_store
     * @param $data
     * @return array
     */
    public function test_update_and_show($data)
    {
        [$admin, $resident] = $data;

        $response = $this->actingAs($admin)->put("/api/residents/{$resident->id}", [
            'fio' => $resident->fio . ' Updated',
            'area' => number_format($resident->area * 10, 2),
            'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
        ]);

        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get("/api/residents/{$resident->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $resident->id,
                'fio' => $resident->fio . ' Updated',
                'area' => number_format($resident->area * 10, 2),
                'start_date' => $resident->start_date->format('Y.m.d H:i:s'),
            ],
        ]);

        return [$admin, $resident];
    }

    /**
     * @depends test_update_and_show
     * @param $data
     * @return array
     */
    public function test_destroy($data)
    {
        [$admin, $resident] = $data;

        $response = $this->actingAs($admin)->delete("/api/residents/{$resident->id}");

        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get("/api/residents/{$resident->id}");

        $response->assertStatus(404);

        return [$admin, $resident];
    }

    /**
     * @depends test_store
     * @param $data
     * @return array
     */
    public function test_index($data) {
        [$admin, $resident] = $data;

        Resident::factory()->count(50)->create();

        $response = $this->actingAs($admin)->get("/api/residents");

        $response->assertOk();

        $response->assertJsonCount(50, 'data');

        return [$admin, $resident];
    }

    /**
     * @depends test_index
     * @param $data
     * @return array
     */
    public function test_delete_all($data) {
        [$admin, $resident] = $data;

        $residents = Resident::all();

        foreach ($residents as $resident) {
            $response = $this->actingAs($admin)->delete("/api/residents/{$resident->id}");
            $response->assertOk();
        }

        $this->assertDatabaseCount('residents', 0);

        return [$admin, $resident];
    }
}
