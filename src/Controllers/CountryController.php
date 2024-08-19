<?php

namespace App\Controllers;

use App\Services\CountryService;

class CountryController {
    private CountryService $service;

    public function __construct() {
        $this->service = new CountryService();
    }

    /*public function getCountries() {
        $countries = json_encode($this->service->getAllCountries());
        echo $countries;
    }*/

    public function getCountries() {
        $filters = [];
        $search = '';
        $sort = '';

        
        if (!empty($_GET['filter'])) {      
            $filters = $_GET['filter'];     //print_r($filters); 
        }
        if (!empty($_GET['search'])) {      
            $search = $_GET['search'];                    //print_r($search);
        }
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];                        //print_r($sort);
        }

        $countries = $this->service->getCountries($filters, $search, $sort);
        // $countries = $this->service->getAllCountries();
        return json_encode($countries);
    }

    public function getCountryByCode($code){
        $country = json_encode($this->service->getCountryByCode($code));
        return $country;
    }

    public function getRegions(){
        $regions = json_encode($this->service->getRegions());
        echo $regions;
    }

    public function getLanguages(){
        $languages = json_encode($this->service->getLanguages());
        echo $languages;
    }
}
