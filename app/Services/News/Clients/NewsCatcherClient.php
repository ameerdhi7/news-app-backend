<?php

namespace App\Services\News\Clients;

use App\Http\Requests\SearchRequest;
use App\Models\Article;
use App\Models\PreferenceOption;
use App\Services\News\Interfaces\NewsClientI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewsCatcherClient implements NewsClientI
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
        $baseUrl = config("api.clients.news_catcher_service.base_url");
        $accessToken = config("api.clients.news_catcher_service.access_token");

        $config = [
            "base_uri" => $baseUrl,
            "headers" => ["X-Api-Key" => $accessToken] //handle auth by attach the auth header
        ];
        return new Client($config);
    }

    public function getNews(): Collection
    {
        $url = "/v2/latest_headlines?countries=US&lang=en";
        $limit = config("api.home_results_limit_per_client");
        $url .= "&page_size={$limit}";
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["articles"];
            return $this->mapResult($articles);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }


    public function search(SearchRequest $searchRequest): Collection
    {
        $limit = config("api.search_results_limit_per_client");
        $url = "/v2/search?lang=en";
        $options = $searchRequest->validated();
        $query = $options["searchQuery"];
        $url .= "&q={$query}";
        $containCategory = isset($options["category"]);
        if ($containCategory) {
            $url .= "&topic={$options["category"]}";
        }
        $containDate = isset($options["date"]);
        if ($containDate) {
            $url .= "&from{$options["date"]}";
        }
        $url .= "&page_size={$limit}";
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["articles"];
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
                "description" => $article["summary"],
                "author" => $article["author"],
                "image_url" => $article["media"],
                "source" => $article["clean_url"],
            ]);
        }, $articles);
        return collect($results);
    }


    public function getByPreferences(Collection $preferences)
    {
        $url = "/v2/top-headlines?country=us";

        //Adding the sources to the query params
        $sources = $preferences->where("type", "source");
        if ($sources->isNotEmpty()) {
            $sourceNamesArray = $sources->pluck("name")->toArray();
            $sourceNames = implode(",", $sourceNamesArray);
            $url .= "&sources=$sourceNames";
        }
        $limit = config("api.preferences_results_limit_per_client");
        if ($limit) {
            $url .= "&page_size=$limit";
        }
        //doing the api call
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $articles = $decodedBody["articles"];
            return $this->mapResult($articles);
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }


    public function getCategories(): array
    {
        /*
         * According to the NEWCatcher documentation and in order to make the categories
         * options more synced with the data sources
        */
        return [
            "energy",
            "gaming",
            "science",
            "food",
            "music"
        ];
    }

    public function getSources(): array
    {
        $url = " /v2/sources?lang=en";
        $limit = config("api.sources_results_limit_per_client");
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $sourcesArray = $decodedBody["sources"];
            //pick 5 sources only
            $sourcesArray = array_slice($sourcesArray, 0, $limit);
            $mappedResults = array_map(function ($item) {
                return new PreferenceOption(["name" => $item["name"], "type" => "source"]);
            }, $sourcesArray);
            return $mappedResults;
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }


    public function getAuthors(): array
    {
        return [];
    }
}
