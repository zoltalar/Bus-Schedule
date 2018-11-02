<?php

use App\Models\Route;
use App\Models\Vehicle;
use Goutte\Client;
use Illuminate\Database\Seeder;

class RoutesSeeder extends Seeder
{
    /**
     * Base URL containing list of routes.
     * 
     * @var string
     */
    protected $url = 'http://rozklady.mpk.krakow.pl';
    
    public function run()
    {
        $client = new Client();        
        $crawler = $client->request('GET', $this->url);        
        $vehicles = Vehicle::all();
        
        foreach ($vehicles as $vehicle) {
            $link = $crawler->selectLink($vehicle->name);
            
            if ($link->getNode(0) !== null) {
               $click = $client->click($link->link());
               
               $text = $click
                    ->filter('.borderTB')
                    ->each(function($node) {
                        return $node->text();
                    });

                if (isset($text[0])) {
                    $name = str_replace('Trasa:', '', $text[0]);
                    $name = trim(strip_tags($name));

                    Route::firstOrCreate([
                        'name' => $name,
                        'vehicle_id' => $vehicle->id
                    ]);
                }
            }
        }
    }
}
