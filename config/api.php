<?php

use App\Services\News\Clients\NewsApiClient;

return [
    "clients" => [
        "news_api_service" => [
            "base_url" => env("NEW_API_SERVICE", "https://newsapi.org"),
            "access_token" => env("NEW_API_TOKEN"),
            "class" => NewsApiClient::class
        ],
        "the_guardian_service" => [
            "base_url" => env("THE_GUARDIAN_SERVICE_ACCESS_TOKEN", "https://content.guardianapis.com/"),
            "access_token" => env("THE_GUARDIAN_SERVICE_BASE_URL"),
            "class" => null
        ]
    ]

];
