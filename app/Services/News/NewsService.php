<?php

namespace App\Services\News;

class NewsService
{
    protected array $clients;

    public function __construct()
    {
        $this->setClients();
    }

    // Method to set the news clients
    protected function setClients(): void
    {
        // Get the news clients configuration from the application config file
        $clients = config("api.clients");

        // Iterate over each client and add it to the clients array if the "class" key is set
        foreach ($clients as $client) {
            if (isset($client["class"])) {
                $this->clients[] = $client["class"];
            }
        }
    }

    // Method to get news from all the clients
    public function getNews(): \Illuminate\Support\Collection
    {
        $results = [];

        // Iterate over each client and fetch the news
        foreach ($this->clients as $client) {
            // Instantiate the client class dynamically and call the getNews method
            $results[] = (new $client)->getNews();
        }

        // Return the collected results as a collection
        return collect($results);
    }
}
