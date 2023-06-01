<?php

namespace App\Services\News\Clients;

use App\Http\Requests\SearchRequest;
use App\Models\Article;
use App\Services\News\Interfaces\NewsClientI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewsDataClient implements NewsClientI
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
        $baseUrl = config("api.clients.news_data_service.base_url");

        $config = [
            "base_uri" => $baseUrl,
        ];
        return new Client($config);
    }

    public function getNews(): Collection
    {
        $url = "/api/1/news?language=en";
        $url = $this->addAuthParam($url);
        $limit = config("api.home_results_limit_per_client");
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["results"];
            $articles = array_slice($articles, 0, $limit);
            return $this->mapResult($articles);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }

    public function search(SearchRequest $searchRequest): Collection
    {
        $url = "/api/1/news?language=en";
        $url = $this->addAuthParam($url);
        $limit = config("api.search_results_limit_per_client");
        $options = $searchRequest->validated();
        $query = $options["searchQuery"];
        $url .= "&q={$query}";
        $containCategory = isset($options["category"]);
        if ($containCategory) {
            $url .= "&category={$options["category"]}";
        }
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["results"];
            $articles = array_slice($articles, 0, $limit);
            return $this->mapResult($articles);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }

    public function mapResult(array $articles): Collection
    {
        $results = array_map(function ($article) {
            return new Article([
                "title" => $article["title"],
                "description" => $article["description"],
                "author" => $article["creator"] ? implode("-", $article["creator"]) : null,
                "image_url" => $article["image_url"],
                "source" => $article["source_id"],
            ]);
        }, $articles);
        return collect($results);
    }

    public function getByPreferences(Collection $preferences)
    {
        $url = "/v2/top-headlines?language=en";
        $url = $this->addAuthParam($url);
        //Adding the categories to the query params
        $categories = $preferences->where("type", "category");
        if ($categories->isNotEmpty()) {
            $categoryNamesArray = $categories->pluck("name")->toArray();
            //take 5 as this the api limit
            $categoryNamesArray = array_slice($categoryNamesArray, 0, 5);
            $categoryNames = implode(",", $categoryNamesArray);
            $url .= "&category=$categoryNames";
        }
        $limit = config("api.preferences_results_limit_per_client");
        //doing the api call
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["results"];
            $articles = array_slice($articles, 0, $limit);
            return $this->mapResult($articles);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }


    public function getCategories(): array
    {
        return [];
    }

    public function getSources(): array
    {
        return [];
    }

    public function getAuthors(): array
    {
        return [];
    }

    private function addAuthParam(string $url): string
    {
        $accessToken = config("api.clients.news_data_service.access_token");
        return "{$url}&apikey={$accessToken}";
    }
}
