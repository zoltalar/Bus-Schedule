<?php

use App\Extensions\Str;
use App\Models\Vehicle;
use Goutte\Client;
use Illuminate\Database\Seeder;

class SchedulesSeeder extends Seeder
{
    /**
     * Base URL containing list of schedules.
     * 
     * @var string
     */
    protected $url = 'http://rozklady.mpk.krakow.pl';
    
    /**
     * Process scraped text.
     * 
     * @param array $text
     * @return array
     */
    protected function process(array $text)
    {
        $data = [
            'stop' => null,
            'times' => []
        ];
        
        $i = 0;
        $words = ['przystanki', 'nz', 'godzina'];
        
        foreach ($text as $item) {
            $i++;
            
            if (in_array(strtolower($item), $words)) {
                continue;
            }
            
            if (is_numeric($item)) {
                $data['times'][] = $item;
            }
        }
        
        return $data;
    }
    
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        $vehicles = Vehicle::findMany([1,2]);
        
        foreach ($vehicles as $vehicle) {
            $link = $crawler->selectLink($vehicle->name);
            
            if ($link->getNode(0) !== null) {
                $click = $client->click($link->link());
                
                $text = $click
                    ->filter('td')
                    ->each(function($node) {
                        if ( ! Str::isHtml($node->html())) {
                            return trim($node->text());
                        }
                        
                        return null;
                    });
                    
                $text = collect($text)
                    ->reject(function($value, $key) {
                        return empty($value);
                    })
                    ->values()
                    ->all();
                        
                $data = $this->process($text);
                
                print_r($text);
            }
        }
    }
}