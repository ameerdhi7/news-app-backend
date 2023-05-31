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

    /**
     * @return Collection<Article>
     */

    public function getNews(): Collection
    {
        $url = "/v2/top-headlines?country=us";
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
        $url = "/v2/everything?";
        $options = $searchRequest->validated();
        $query = $options["searchQuery"];
        $url .= "q={$query}";
        $containSource = isset($options["source"]);
        if ($containSource) {
            $url .= "&sources={$options["source"]}";
        }
        $url .= "&pageSize=5";
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


    /**
     * @return array|PreferenceOption[]
     */
    public function getCategories(): array
    {
        /*
         * According to the NewsApi documentation and in order to make the categories
         * options more synced with the data sources
        */
        $categories = [
            "business",
            "entertainment",
            "general",
            "health",
            "science",
            "sports",
            "technology",
        ];

        $mappedResults = array_map(function ($item) {
            return new PreferenceOption(["name" => $item, "type" => "category"]);
        }, $categories);
        return $mappedResults;
    }

    /**
     * @return array|PreferenceOption[]
     */
    public function getSources(): array
    {
        $url = " /v2/top-headlines/sources";
        try {
            $response = $this->client->get($url);
            $body = $response->getBody();
            $decodedBody = json_decode($body, true);
            $sourcesArray = $decodedBody["sources"];
            $mappedResults = array_map(function ($item) {
                return new PreferenceOption(["name" => $item["name"], "type" => "source"]);
            }, $sourcesArray);
            return $mappedResults;
        } catch (GuzzleException $httpClientException) {
            Log::error($httpClientException->getMessage());
        }
    }

    /**
     * @return array|PreferenceOption[]
     */
    public function getAuthors(): array
    {
        //scrap authors from the main get news results
        $newsCollection = $this->getNews();
        $authors = $newsCollection
            ->where("author", " != ", null)
            ->pluck("author")
            ->toArray();
        $mappedResults = array_map(function ($item) {
            return new PreferenceOption(["name" => $item, "type" => "author"]);
        }, $authors);
        return $mappedResults;
    }

    public function getByPreferences(Collection $preferences)
    {
        $url = "/v2/top-headlines?country=us";


        //Adding the categories to the query params
        $categories = $preferences->where("type", "category");
        if ($categories->isNotEmpty()) {
            $categoryNamesArray = $categories->pluck("name")->toArray();
            foreach ($categoryNamesArray as $categoryName) {
                $url .= "&category=$categoryName";
            }
        }

        //Adding the categories to the query params
        $sources = $preferences->where("type", "source");
        if ($sources->isNotEmpty()) {
            $sourceNamesArray = $sources->pluck("name")->toArray();
            foreach ($sourceNamesArray as $sourceName) {
                $url .= "&sources=$sourceName";
            }
        }

        $limit = config("api.preferences_results_limit_per_client");
        if ($limit) {
            $url .= "&pageSize=$limit";
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
}
