<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BeerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Martins',
            'price' => 2.20,
            'rating_avg' => 1.43,
            'reviews' => 24,
            'image' => 'https://pivovarmartins.sk/wp-content/uploads/2020/12/capak10-683x1024.png'
        ];
    }
}
