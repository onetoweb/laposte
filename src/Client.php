<?php

namespace Onetoweb\LaPoste;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Onetoweb\LaPoste\Exception\RequestException;

/**
 * Onetoweb La Poste Api Client.
 * 
 * @author Jonathan van 't Ende <jvantende@onetoweb.nl>
 * @copyright Onetoweb B.V.
 */
class Client
{
    const BASE_URI = 'https://api.laposte.fr';
    
    const METHOD_GET = 'GET';
    
    /**
     * @var string
     */
    private $apiKey;
    
    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * @param string $endpoint
     * @param array $query = []
     * 
     * @return mixed
     */
    public function get(string $endpoint, array $query = [])
    {
        return $this->request(self::METHOD_GET, $endpoint, $query);
    }
    
    /**
     * @param string $method
     * @param string $endpoint
     * @param array $query = []
     * 
     * @return mixed
     */
    public function request(string $method = self::METHOD_GET, string $endpoint, array $query = [])
    {
        try {
            
            if (count($query) > 0) {
                $endpoint .= '?'. http_build_query($query);
            }
            
            $client = new GuzzleClient([
                'base_uri' => self::BASE_URI
            ]);
            $response = $client->request($method, $endpoint, [
                RequestOptions::HEADERS => [
                    'X-Okapi-Key' => $this->apiKey
                ],
            ]);
            
            return json_decode($response->getBody()->getContents());
            
        } catch (GuzzleRequestException $guzzleRequestException) {
            
            if ($guzzleRequestException->hasResponse()) {
                
                throw new RequestException($guzzleRequestException->getResponse()->getBody()->getContents(), $guzzleRequestException->getCode(), $guzzleRequestException);
                
            } else {
                
                throw $guzzleRequestException;
                
            }
        }
    }
}