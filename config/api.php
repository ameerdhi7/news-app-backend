<?php

use App\Services\News\Clients\NewsApiClient;

return [
    "clients" => [
        "news_api_service" => [
            "base_url" => env("NEW_API_SERVICE", "https://newsapi.org"),
            "access_token" => env("NEW_API_TOKEN"),
            "class" => NewsApiClient::class
        ],
    ],
    "preferences_results_limit_per_client" => 10,

];
