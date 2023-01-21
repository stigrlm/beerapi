<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BeerTest extends TestCase
{
    use RefreshDatabase;

    const TEST_BEER = [
        'name' => 'Martins',
        'price' => 2.20,
        'rating_avg' => 1.43,
        'reviews' => 24,
        'image' => 'https://pivovarmartins.sk/wp-content/uploads/2020/12/capak10-683x1024.png'
    ];

    public function test_create_beer()
    {     
        $response = $this->postJson('/api/beer', $this::TEST_BEER);

        $response
            ->assertStatus(201)
            ->assertJson($this::TEST_BEER);
    }

    public function test_get_beer()
    {
        $beer = \App\Models\Beer::factory()->create($this::TEST_BEER);
        $response = $this->getJson('/api/beer/' . $beer->id);

        $response
            ->assertStatus(200)
            ->assertJson($this::TEST_BEER);
    }

    public function test_update_beer()
    {
        $beer = \App\Models\Beer::factory()->create($this::TEST_BEER);
        $updateData = ['price' => 2.50];
        $response = $this->putJson('/api/beer/' . $beer->id, $updateData);

        $response
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    public function test_delete_beer()
    {
        $beer = \App\Models\Beer::factory()->create($this::TEST_BEER);
        $response = $this->deleteJson('/api/beer/' . $beer->id);

        $response
            ->assertStatus(204);
    }
}
