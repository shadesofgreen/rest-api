<?php

require '../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use App\Controllers\CountryController;

$countryController = new CountryController();  
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

 
  
switch ($_SERVER['REQUEST_METHOD'] == 'GET') {
    case 'GET':
        if ($uri == '/api/countries') {  
            $countryController->getCountries();
        } elseif (preg_match('/^\/api\/countries\/([A-Za-z]{2,3})$/', $uri, $matches)) {
            $countryController->getCountryByCode($matches[1]);
        } elseif ($uri == '/api/regions') {
            $countryController->getRegions();
        } elseif ($uri == '/api/languages') {
            $countryController->getLanguages();
        };
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
        break;
}
   