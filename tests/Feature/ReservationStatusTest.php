<?php

namespace Tests\Feature;

class ReservationStatusTest extends TestCase
{
    public function testIndexReturnsSuccessResponse()
    {
        $response = $this->getJson('/api/reservation-statuses');

        $response->assertStatus(200);
    }
}
