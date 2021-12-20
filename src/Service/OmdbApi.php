<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApi
{
    private $apiUrl = 'https://www.omdbapi.com/?apiKey=83bfb8c6';
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * Méthode permettant de retourner les informations (issues d'une API)
     * d'une série en fonction de son title
     *
     * @param string $title
     * @return Array
     */
    public function fetch($title)
    {
        $response = $this->client->request(
            'GET',
            // https://www.omdbapi.com/?apiKey=83bfb8c6&t=Scrubs
            $this->apiUrl . '&t=' . $title
        );

        // On retourne les informations de la série 
        // sous forme de tableau associatif
        return $response->toArray();
    }
}
