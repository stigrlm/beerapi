<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Beer;

class FetchBeerData extends Command
{
    const BEER_API_URI = 'https://api.sampleapis.com/beers/stouts';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beers:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch beer data from external api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get($this::BEER_API_URI);

        if ( !( $response->successful() ) ) {
            echo (
                "Unable to get success response from api: " . $this::BEER_API_URI 
                . ". Returned response: " . $response 
            );
            return;
        }

        $beerData = json_decode($response, true);

        echo ("Request successfull, got " . count($beerData) . " items");
        
        /**
         * Implementation using Model to handle the transactions, however it uses two queries
         * per item, which is not really efficient. If performance is the concern, it would be
         * more appropriate to use bulk upsert query (DB::table('beers)->upsert), preferably
         * with chunking data if number of items is significant (magnitude of thousands and more)
         */
        foreach ($beerData as $item) {
            $id = $item['id'];

            $beerDataClean = [
                'name' => $item['name'],
                'price' => (int) ltrim($item['price'], '$'),
                'rating_avg' => $item['rating']['average'],
                'reviews' => $item['rating']['reviews'],
                'image' => $item['image']
            ];

            $beer = Beer::find($id);
            if ($beer) {
                $beer->update($beerDataClean);
            } else {
                Beer::create(array_merge(['id' => $id], $beerDataClean));
            }
        }
    }
}
