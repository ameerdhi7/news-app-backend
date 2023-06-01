<?php

use App\Services\News\Clients\NewsApiClient;
use App\Services\News\Clients\NewsCatcherClient;
use App\Services\News\Clients\NewsDataClient;

return [
    "clients" => [
//        "news_api_service" => [
//            "base_url" => env("NEWS_API_BASE_URL", "https://newsapi.org"),
//            "access_token" => env("NEWS_API_TOKEN"),
//            "class" => NewsApiClient::class
//        ],
        "news_catcher_service" => [
            "base_url" => env("NEWS_CATCHER_BASE_URL", "https://api.newscatcherapi.com/"),
            "access_token" => env("NEWS_CATCHER_ACCESS_TOKEN"),
            "class" => NewsCatcherClient::class
        ],
//        "news_data_service" => [
//            "base_url" => env("NEWS_DATA_BASE_URL", "https://api.newscatcherapi.com/"),
//            "access_token" => env("NEWS_DATA_ACCESS_TOKEN"),
//            "class" => NewsDataClient::class
//        ]
    ],

    "preferences_results_limit_per_client" => 10,

    "sources_results_limit_per_client" => 5,

    "home_results_limit_per_client" => 10,

    "search_results_limit_per_client" => 5,
];
