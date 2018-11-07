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
            'times' => [
                'weekdays' => [],
                'saturdays' => [],
                'holidays' => []
            ]
        ];
        
        $map = [
            'weekdays' => 1,
            'saturdays' => 2,
            'holidays' => 3
        ];
        
        $headers = $this->detectHeaders($text);
        $count = count($headers);
        $i = reset($headers);
        $header = key($headers);
        $text = array_slice($text, $i);
        
        $i = 0;
        $start = -1;
        $hours = range(1, 23);
        
        foreach ($text as $item) {
            if (strpos($item, 'Pabijan Grzegorz') !== false) {
                break;
            }
            
            if (is_numeric($item) && $start === -1) {
                $hour = (int) $item;
                
                if (in_array($hour, $hours)) {
                    $start = $i;
                }
            }
            
            if ($start !== -1) {
                $modulo = ($i-$start)%$count;
                
                if ($modulo === 0 && ! empty($item)) {
                    $hour = (int) $item;
                    
                    $data['times']['weekdays'][$hour] = [];
                    $data['times']['saturdays'][$hour] = [];
                    $data['times']['holidays'][$hour] = [];            
                } elseif ( ! empty($item)) {
                    if (strpos($item, ' ') !== false) {
                        $minutes = explode(' ', $item);
                    } else {
                        $minutes = [$item];
                    }
                    
                    $key = null;
                    
                    foreach ($map as $_key => $value) {
                        if ($modulo === $value) {
                            $key = $_key;
                            break;
                        }
                    }
                    
                    if ($key !== null) {
                        end($data['times'][$key]);
                        $hour = key($data['times'][$key]);

                        foreach ($minutes as $minute) {
                            $minute = (int) $minute;                        
                            $data['times'][$key][$hour][] = $minute;
                        }
                    }
                }
            }
            
            $i++;
        }
        
        return $data;
    }
    
    /**
     * Detect schedule header location.
     * 
     * @param array $text
     * @return array
     */
    protected function detectHeaders(array $text) 
    {
        $headers = [
            'Godzina' => -1,
            'Dzień powszedni' => -1,
            'Soboty' => -1,
            'Święta' => -1
        ];
        
        $names = array_keys($headers);
        $i = 0;
        
        foreach ($text as $item) {
            if (in_array($item, $names)) {
                $headers[$item] = $i;
            }
            
            $i++;
        }
        
        return $headers;
    }
    
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        $vehicles = Vehicle::findMany(range(0,2));
        
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
                   
                $data = $this->process($text);
                
                print_r($data);
            }
        }
    }
}