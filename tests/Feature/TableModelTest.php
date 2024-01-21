<?php

namespace Tests\Feature;

use App\Models\TableModel;

class TableModelTest extends TestCase
{
    protected $endpoint;

    public function setUp(): void
    {
        parent::setUp();
        $this->endpoint = '/api/tables';
    }
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
    }

    public function testShow(): void
    {
        $table = TableModel::factory()->create();
        $response = $this->getJson($this->endpoint . '/' . $table->id);

        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $params = [
            'table_number' => 1,
            'capacity' => 4,
            'is_available' => true
        ];

        $response = $this->postJson($this->endpoint, $params);
        $response->assertStatus(201);

        $this->assertDatabaseHas('table_models', [
            'table_number' => $params['table_number'],
            'capacity' => $params['capacity'],
            'is_available' => $params['is_available'],
        ]);
    }

    public function testFailedValidationStore(): void
    {
        $params = [
            'capacity' => 4,
            'is_available' => true
        ];

        $response = $this->postJson($this->endpoint, $params);
        $response->assertStatus(422);
    }

    public function testUpdate(): void
    {
        $table = TableModel::factory()->create();
        $response = $this->putJson($this->endpoint . '/' . $table->id, [
            'capacity' => 10
        ]);

        $response->assertStatus(200);
    }

    public function testDestroy(): void
    {
        $table = TableModel::factory()->create();
        $response = $this->deleteJson($this->endpoint . '/' . $table->id . '/delete');
        $response->assertStatus(200);
    }
}
