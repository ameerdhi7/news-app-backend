<?php

namespace App\Services\News;

class NewsService
{
    public function getNews()
    {
        $resultsPool = [];
        $clients = config("api.clients");
        foreach ($clients as $client) {
            if (isset($client["class"])) {
                $clientInstance = new $client["class"];
                $resultsPool[] = $clientInstance->getNews();
            }
        }
        return collect($resultsPool);
    }
}
