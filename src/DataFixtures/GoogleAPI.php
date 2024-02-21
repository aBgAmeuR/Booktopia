<?php

namespace App\DataFixtures;

use Symfony\Component\HttpClient\HttpClient;

class GoogleAPI
{
    public function getBooks(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.googleapis.com/books/v1/volumes?q=-zaujazopfjopaz&filter=paid-ebooks&langRestrict=fr&maxResults=40&startIndex=50');

        if ($response->getStatusCode() == 200) {
            return $response->toArray();
        }

        return [];
    }
}
