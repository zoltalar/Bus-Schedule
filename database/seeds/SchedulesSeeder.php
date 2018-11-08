<?php

use App\Extensions\Str;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Stop;
use App\Models\Vehicle;
use Carbon\Carbon;
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
     * Stops and routes cache.
     * 
     * @var array
     */
    protected $cache = [
        'stops' => [],
        'routes' => []
    ];

    /**
     * Process scraped text to retrieve schedule times.
     * 
     * @param array $text
     * @return array
     */
    protected function process(array $text)
    {
        $data = [
            'weekdays' => [],
            'saturdays' => [],
            'holidays' => []
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
                    
                    $data['weekdays'][$hour] = [];
                    $data['saturdays'][$hour] = [];
                    $data['holidays'][$hour] = [];            
                } elseif ( ! empty($item)) {
                    $minutes = [];
                    
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
                        end($data[$key]);
                        $hour = key($data[$key]);

                        foreach ($minutes as $minute) {
                            $minute = (int) $minute;                        
                            $data[$key][$hour][] = $minute;
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
    
    /**
     * Detect stop name.
     * 
     * @param array $text
     * @return string|null
     */
    protected function detectStop(array $text, $check = false)
    {
        $name = null;
        
        $words = [
            'Przystanki',
            'NZ',
            'Godzina',
            'Dzień powszedni',
            'Soboty',
            'Święta'
        ];
        
        foreach ($text as $item) {
            if (in_array($item, $words)) {
                continue;
            }
            if (starts_with($item, 'Trasa:')) {
                continue;
            }
            if (strpos($item, 'Pabijan Grzegorz') !== false) {
                continue;
            }
            if ( ! empty($item) && ! is_numeric($item)) {
                $name = $item;
                
                if ($check) {
                    $stop = Stop::where('name', $name)->first();
                    
                    if ($stop !== null) {
                        break;
                    } else {
                        continue;
                    }
                } else {
                    break;
                }
            }
        }
        
        return $name;
    }

    /**
     * Detect route name.
     * 
     * @param array $text
     * @param string|null
     */
    protected function detectRoute(array $text)
    {
        $name = null;
        
        foreach ($text as $item) {
            if (starts_with($item, 'Trasa:')) {
                $name = str_replace('Trasa:', '', $item);
                $name = trim(strip_tags($name));
                
                break;
            }
        }
        
        return $name;
    }
    
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        $vehicles = Vehicle::findMany([25]);
        
        foreach ($vehicles as $vehicle) {
            $link = $crawler->selectLink($vehicle->name);
            
            if ($link->getNode(0) !== null) {
                $click = $client->click($link->link());
                
                $text = $click
                    ->filter('td')
                    ->each(function($node) {
                        $text = trim($node->text());
                        
                        if ( ! Str::isHtml($node->html()) || starts_with($text, 'Trasa:')) {
                            return $text;
                        }
                        
                        return null;
                    });
                   
                $data = $this->process($text);
                $stopName = $this->detectStop($text);
                $routeName = $this->detectRoute($text);
                
                if (count($data['weekdays']) > 0) {
                    for ($i=1; $i<=5; $i++) {
                        foreach ($data['weekdays'] as $hour => $minutes) {                            
                            foreach ($minutes as $minute) {
                                $dt = Carbon::create(null, null, null, $hour, $minute);
                                
                                $stopId = null;
                                $routeId = null;
                                
                                if ( ! array_key_exists($stopName, $this->cache['stops'])) {
                                    $stop = Stop::where('name', $stopName)->first();
                                    
                                    if ($stop !== null) {
                                        $this->cache['stops'][$stopName] = $stop->id;
                                    }
                                }
                                
                                if (array_key_exists($stopName, $this->cache['stops'])) {
                                    $stopId = $this->cache['stops'][$stopName];
                                }
                                
                                if ( ! array_key_exists($routeName, $this->cache['routes'])) {
                                    $route = Route::where('name', $routeName)->first();
                                    
                                    if ($route !== null) {
                                        $this->cache['routes'][$routeName] = $route->id;
                                    }
                                }
                                
                                if (array_key_exists($routeName, $this->cache['routes'])) {
                                    $routeId = $this->cache['routes'][$routeName];
                                }
                                
                                if ($stopId !== null && $routeId !== null) {
                                    $schedule = [
                                        'vehicle_id' => $vehicle->id,
                                        'stop_id' => $stopId,
                                        'route_id' => $routeId,
                                        'day' => $i,
                                        'time' => $dt->toTimeString()
                                    ];

                                    Schedule::create($schedule);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}