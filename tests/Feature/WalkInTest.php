<?php

namespace Tests\Feature;

use App\Models\ReservationStatus;
use App\Models\TableModel;
use App\Models\User;
use App\Models\WalkIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

class WalkInTest extends TestCase
{
    protected $user;
    protected $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->endpoint = 'api/walk-ins';
    }


    public function testIndex(): void
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $table = TableModel::factory()->create();
        ReservationStatus::factory()->create([
            'name' => 'On Progress'
        ]);

        $user = User::factory()->create();

        $params = [
            'table_id' => $table->id,
            'request_date' => Carbon::now()->format('Y-m-d'),
            'request_time' => Carbon::now()->format('H:i:s'),
            'username' => $user->name
        ];

        $response = $this->postJson($this->endpoint, $params);


        $response->assertStatus(201);

        $this->assertDatabaseHas('walk_ins', [
            'table_id' => $params['table_id']
        ]);
    }

    public function testFailedValidationStore(): void
    {
        $params = [
            'table_id' => null,
            'request_date' => Carbon::now()->format('Y-m-d'),
            'request_time' => Carbon::now()->format('H:i:s')
        ];

        $response = $this->postJson($this->endpoint, $params);

        $response->assertStatus(422);
    }

    public function testUpdate(): void
    {
        $walkIn = WalkIn::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $walkIn->id, [
            'request_time' => '12:00:00'
        ]);

        $response->assertStatus(200);
    }

    public function testDestroy(): void
    {
        $walkIn = WalkIn::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $walkIn->id . '/delete');

        $response->assertStatus(200);
    }
}
