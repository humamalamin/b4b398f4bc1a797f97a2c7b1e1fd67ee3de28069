<?php

namespace Tests\Feature;

use App\Jobs\Reservation\CancelBookingJob;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\TableModel;
use App\Models\User;
use Database\Factories\ReservationStatusFactory;
use Database\Factories\TableModelFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

class ReservationTest extends TestCase
{
    protected $user;
    protected $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        Queue::fake();

        $this->endpoint = 'api/reservations';
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
            'name' => 'Booked'
        ]);

        $params = [
            'table_id' => $table->id,
            'reservation_date' => Carbon::now()->format('Y-m-d'),
            'reservation_time' => Carbon::now()->format('H:i:s')
        ];

        $response = $this->postJson($this->endpoint, $params);

        Queue::assertPushed(CancelBookingJob::class);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reservations', [
            'table_id' => $params['table_id']
        ]);
    }

    public function testFailedValidationStore(): void
    {
        $params = [
            'table_id' => null,
            'reservation_date' => Carbon::now()->format('Y-m-d'),
            'reservation_time' => Carbon::now()->format('H:i:s')
        ];

        $response = $this->postJson($this->endpoint, $params);

        $response->assertStatus(422);
    }

    public function testUpdate(): void
    {
        $reservation = Reservation::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $reservation->id, [
            'reservation_time' => '12:00:00'
        ]);

        $response->assertStatus(200);
    }

    public function testDestroy(): void
    {
        $reservation = Reservation::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $reservation->id . '/delete');

        $response->assertStatus(200);
    }
}
