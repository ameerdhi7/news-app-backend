<?php

namespace App\Services\Article\Clients;

use App\Services\Article\Interfaces\NewsClientI;
use GuzzleHttp\Client;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class NewsApiClient implements NewsClientI
{
    protected Client $client;

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        $baseUrl = config("api.client.news_api_service.base_url");
        $accessToken = config("api.client.news_api_service.access_token");

        $config = [
            "base_url" => $baseUrl,
            "headers" => ["X-Api-Key" => $accessToken] //handle auth by attach the auth header
        ];
        return new Client($config);
    }

    public function getNews(): array
    {
        $url = "everything";
        try {
            $response = $this->client->get("/everything");
        } catch (HttpClientException $httpClientException) {
            return false;
        }
    }

    public function search(): array
    {
        // TODO: Implement search() method.
    }

    function mapResult(): Collection
    {
        // TODO: Implement mapResult() method.
    }

    function getNewsByUserPreferences(): Collection
    {
        // TODO: Implement getNewsByUserPreferences() method.
    }
}
