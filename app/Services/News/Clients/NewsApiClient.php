<?php

namespace App\Services\News\Clients;

use App\Models\Article;
use App\Services\News\Interfaces\NewsClientI;
use Dflydev\DotAccessData\Data;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $baseUrl = config("api.clients.news_api_service.base_url");
        $accessToken = config("api.clients.news_api_service.access_token");

        $config = [
            "base_uri" => $baseUrl,
            "headers" => ["X-Api-Key" => $accessToken] //handle auth by attach the auth header
        ];
        return new Client($config);
    }

    public function getNews(): Collection
    {
        $url = "/v2/top-headlines?country=us";
        try {
            $response = $this->client->get($url);
            return $this->mapResult($response);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }

    public function search(): array
    {
        // TODO: Implement search() method.
    }

    public function mapResult(Response $response): Collection
    {
        $body = $response->getBody();
        $decodedBody = json_decode($body, true);
        $articles = $decodedBody["articles"];
        //map results to adhere to Article form
        $mappedArticles = array_map(function ($article) {
            $map = [
                "title" => $article["title"],
                "description" => $article["title"],
                "source" => $article["source"]["name"],
                "author" => $article["author"],
                "image_url" => $article["urlToImage"],
            ];
            return new Article($map);
        }, $articles);
        return collect($mappedArticles);
    }

    function getNewsByUserPreferences(): Collection
    {
    }
}
