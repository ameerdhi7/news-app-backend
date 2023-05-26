<?php

namespace App\Repositories;

use App\Repositories\Interfaces\NewsRepositoryI;
use App\Services\News\NewsService;
use Illuminate\Support\Collection;

class NewsRepository implements NewsRepositoryI
{
    public function getHomeFeed(): Collection
    {
        $newsService = new NewsService();
        return $newsService->getNews();
    }
}
