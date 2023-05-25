<?php

return [
    "clients" => [
        "news_api_service" => [
            "base_url" => env("NEW_API_SERVICE",),
            "access_token" => env("NEW_API_TOKEN", ""),
        ],
        "the_guardian_service" => [
            "base_url" => env("The_Guardian_Service_BASE_URL", "https://content.guardianapis.com/"),
            "access_token" => env("The_Guardian_Service_ACCESS_TOKEN", "c4ce689f-1122-41f9-903e-26747f7fe5a9")
        ]
    ]

];
