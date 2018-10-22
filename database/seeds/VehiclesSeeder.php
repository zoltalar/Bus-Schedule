<?php

use App\Constants\VehicleTypes;
use App\Models\Vehicle;
use Goutte\Client;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Base URL containing list of transportation vehicles.
     * 
     * @var string
     */
    protected $url = 'http://rozklady.mpk.krakow.pl';
    
    /**
     * Tram IDs.
     * 
     * @link http://rozklady.mpk.krakow.pl
     * 
     * @var array
     */
    protected function trams()
    {
        return [
            1, 2, 3, 
            5, 6, 9, 
            10, 11, 14, 
            16, 17, 18, 
            19, 21, 22, 
            24, 50, 52, 
            62, 69, 73, 
            74, 78
        ];
    }
    
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        
        $names = $crawler
            ->filter('a')
            ->each(function($node) {
                $query = parse_url($node->attr('href'), PHP_URL_QUERY);
                parse_str($query, $args);
                $name = null;
                
                if (isset($args['linia'])) {
                    $name = trim($args['linia']);
                }
                
                return $name;
            });
            
       foreach ($names as $name) {
           if ( ! empty($name)) {
                $vehicle = [
                    'name' => $name,
                    'type' => VehicleTypes::BUS
                ];

                if (in_array(intval($name), $this->trams())) {
                    $vehicle['type'] = VehicleTypes::TRAM;
                }

                Vehicle::firstOrCreate($vehicle);
            }
       }     
    }
}
