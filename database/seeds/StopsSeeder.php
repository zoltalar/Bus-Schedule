<?php

use App\Models\Stop;
use Goutte\Client;
use Illuminate\Database\Seeder;

class StopsSeeder extends Seeder
{
    /**
     * URL containing list of bus stops.
     * 
     * @var string
     */
    protected $url = 'http://rozklady.mpk.krakow.pl';
    
    public function run()
    {
        $client = new Client();
        
        $crawler = $client->request('GET', $this->url);
        $crawler = $client->click($crawler->selectLink('Przystanki')->link());
        
        $nodes = $crawler
            ->filter('a')
            ->each(function($node) {
                return $node;
            });
        
        foreach ($nodes as $node) {
            $query = parse_url($node->attr('href'), PHP_URL_QUERY);
            parse_str($query, $args);
            
            if (isset($args['przystanek']) && ! empty($args['przystanek'])) {
                $uid = trim($args['przystanek']);
                $name = trim($node->text());
                
                Stop::firstOrCreate([
                    'uid' => $uid,
                    'name' => $name
                ]);
            }
        }
    }
}