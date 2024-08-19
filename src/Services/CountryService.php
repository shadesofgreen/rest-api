<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CountryService {
    private Client $client;
    private FilesystemAdapter $cache;
    private string $apiUrl;
    private string $name;
   

    public function __construct() {
        $this->client = new Client();
        $this->cache = new FilesystemAdapter();
        $this->apiUrl = getenv('API_BASE_URL') ?: 'https://restcountries.com/v3.1';
    }

    public function getAllCountries(): array {
        return $this->cache->get('countries_all', function (ItemInterface $item) {
            $item->expiresAfter(3600); // Cache for 1 hour
            $response = $this->client->get($this->apiUrl . '/all');
            return json_decode($response->getBody()->getContents(), true);
        });
    }
    
    public function getCountries($filters = '', $search = '', $sort = '', $page = 1, $perPage = 10) { 
        $countries = $this->getAllCountries();
         
       
        // filter... Using country name for now but it can be expanded to include other values
        if (!empty($filters)) {  
            $this->name = ucfirst($filters);   
            var_dump(array_filter($countries, function($v, $k) {
                return  $v['name']['official'] == $this->name || $v['name']['common'] == $this->name;
            }, ARRAY_FILTER_USE_BOTH));
        } 
       
        // Searching
        if (!empty($search)) {     
            $this->name = ucfirst($search);
            var_dump(array_filter($countries, function($v, $k) {
                return  $k == 'b' || $v['name']['common'] == $this->name;
            }, ARRAY_FILTER_USE_BOTH));
        }

        
        // Sorting
        if (!empty($sort)) { 

            usort($countries, function ($v, $k) use ($sort) {
                return strcmp($v['name']['common'], $k['name']['common']);
            });

        }
        $countries = json_encode($countries);  
        if(empty($sort) && empty($search) && empty($filters)){print_r($countries);}

        // pagination
        $offset = ($page - 1) * $perPage;
        $paginatedCountries = array_slice($countries, $offset, $perPage);
        // return $paginatedCountries;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        $paginatedCountries = getCountries($page, $perPage);

        header('Content-Type: application/json');
        echo json_encode($paginatedCountries);
        // return  json_encode($countries);  
    }

    public function getCountryByCode(string $code): array {
        return $this->cache->get('country_' . $code, function (ItemInterface $item) use ($code) {
            $item->expiresAfter(3600); // Cache for 1 hour
            $response = $this->client->get($this->apiUrl . "/alpha/$code");
            return json_decode($response->getBody()->getContents(), true);
        });
    }

    public function getRegions(): array {
        return $this->cache->get('regions_all', function (ItemInterface $item) {
            $item->expiresAfter(3600); // Cache for 1 hour
            $countries = $this->getAllCountries();
            $regions = [];

            foreach ($countries as $country) {
                $region = $country['region'];
                if (!isset($regions[$region])) {
                    $regions[$region] = [];
                }
                $regions[$region][] = $country['name']['common'];
            }

            return $regions;
        });
    }

    public function getLanguages(): array {
        return $this->cache->get('languages_all', function (ItemInterface $item) {
            $item->expiresAfter(3600); // Cache for 1 hour
            $countries = $this->getAllCountries();
            $languages = [];

            foreach ($countries as $country) {
                foreach ($country['languages'] ?? [] as $lang) {
                    if (!isset($languages[$lang])) {
                        $languages[$lang] = [];
                    }
                    $languages[$lang][] = $country['name']['common'];
                }
            }

            return $languages;
        });
    }
}
