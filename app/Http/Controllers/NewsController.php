<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\HomeArticleCollection;
use App\Http\Resources\PreferenceOptionsCollection;
use App\Repositories\Interfaces\NewsRepositoryI;

class NewsController extends Controller
{
    public function __construct(
        public NewsRepositoryI $newsRepository
    )
    {
    }

    /**
     * @return HomeArticleCollection
     */
    public function home(): HomeArticleCollection
    {
        $resultCollection = $this->newsRepository->getHomeFeed();

        return new HomeArticleCollection($resultCollection);
    }


    public function preferencesOptions(): PreferenceOptionsCollection
    {
        $resultCollection = $this->newsRepository->getPreferencesOptions();
        return new PreferenceOptionsCollection($resultCollection);
    }

    public function search(SearchRequest $searchRequest): HomeArticleCollection
    {
        $resultCollection = $this->newsRepository->search(searchRequest: $searchRequest);
        return new HomeArticleCollection($resultCollection);
    }
}
