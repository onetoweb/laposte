<?php

require 'vendor/autoload.php';

use Onetoweb\LaPoste\Client;
use Onetoweb\LaPoste\Exception\RequestException;

$apiKey = 'api_key';

$client = new Client($apiKey);

try {
    
    $addresses = $client->get('/controladresse/v1/adresses', [
        'q' => '116 avenue du Président Kennedy 75220 Paris Cedex 16'
    ]);
    
    foreach ($addresses as $address) {
        
        $addressDetail = $client->get("/controladresse/v1/adresses/{$address->code}");
        
    }
    
} catch (RequestException $requestException) {
    
    $errorResponse = json_decode($requestException->getMessage());
    
}