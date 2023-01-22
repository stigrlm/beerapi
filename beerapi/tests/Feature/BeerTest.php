<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Beer;

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

    private function seedBeers()
    {
        $beersData = [
            $this::TEST_BEER,
            [
                'name' => 'Urpiner',
                'price' => 1.80,
                'rating_avg' => 1.6,
                'reviews' => 51,
                'image' => 'https://napojejason.sk/wp-content/uploads/2021/12/ShotType1_540x540-2.jpg'
            ],
            [
                'name' => 'Martiner',
                'price' => 1.60,
                'rating_avg' => 2.1,
                'reviews' => 15,
                'image' => 'https://cdn.metro-group.com/sk/sk_pim_15186001001_00'
            ]
        ];

        foreach ($beersData as $beerData)
        {
            Beer::factory()->create($beerData);
        }
    }

    public function test_create_beer()
    {     
        $response = $this->postJson('/api/beer', $this::TEST_BEER);

        $response
            ->assertStatus(201)
            ->assertJson($this::TEST_BEER);
    }

    public function test_get_beer()
    {
        $beer = Beer::factory()->create($this::TEST_BEER);
        $response = $this->getJson('/api/beer/' . $beer->id);

        $response
            ->assertStatus(200)
            ->assertJson($this::TEST_BEER);
    }

    public function test_update_beer()
    {
        $beer = Beer::factory()->create($this::TEST_BEER);
        $updateData = ['price' => 2.50];
        $response = $this->putJson('/api/beer/' . $beer->id, $updateData);

        $response
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    public function test_delete_beer()
    {
        $beer = Beer::factory()->create($this::TEST_BEER);
        $response = $this->deleteJson('/api/beer/' . $beer->id);

        $response
            ->assertStatus(204);
    }

    public function test_list_beers()
    {
        $this->seedBeers();

        $response = $this->getJson('/api/beers',);

        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_list_beers_with_filters()
    {
        /*
        * TODO: debug why during testing with filters, these are not taken into account by 
        * getJson method. It behaves like if no data were sent in the request body. Whereas
        * when testing from Postman with same request body, filtering and ordering works as
        * expected. It might to do with Laravel TestCase not issuing real requests over network
        * (as per documenation it should be simulated) or the way the database is handled with
        * RefreshDatabase trait or eventually something else I am missing at the moment.
        * I keep the test here even though its failing, at least to illustrate api usage 
        * with filters.
        */
        $this->seedBeers();

        $response = $this->getJson(
            '/api/beers',
            [
                'name_filter' => 'martin',
                'ordering_columns' => ['price' => 'asc', 'rating_avg' => 'desc']
            ]
        );

        $response
            ->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJson([
                ['name' => 'Martiner'],
                ['name' => 'Martins']
            ]);
    }
}
