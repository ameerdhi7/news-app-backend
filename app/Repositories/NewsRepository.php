<?php

namespace App\Repositories;

use App\Http\Requests\SearchRequest;
use App\Models\PreferenceOption;
use App\Repositories\Interfaces\NewsRepositoryI;
use App\Services\News\FetchNewsService;
use Illuminate\Support\Collection;

class NewsRepository implements NewsRepositoryI
{
    /**
     * @return Collection
     */
    public function getHomeFeed(): Collection
    {
        $newsService = new FetchNewsService();
        return $newsService->getNews();
    }

    /**
     * @return Collection
     */

    public function getPreferencesOptions(): Collection
    {
        $fields = ["id","name", "type"];
        // all categories should be returned
        $allCategories = PreferenceOption::select($fields)
            ->where("type", "category")
            ->get();

        // take 20 for sources and authors
        $authors = PreferenceOption::select($fields)
            ->where("type", "author")
//            ->recent()
            ->take(20)
            ->get();
        $sources = PreferenceOption::select($fields)
            ->where("type", "source")
//            ->recent()
            ->take(20)
            ->get();

        // merge results
        $mergedCollection = array_merge(
            $allCategories->toArray(),
            $authors->toArray(),
            $sources->toArray(),
        );
        $mergedCollection = collect($mergedCollection);
        //group by type
        $groupedByType = $mergedCollection->groupBy("type");

        return $groupedByType;
    }

    /**
     * @return Collection
     */
    public function search(SearchRequest $searchRequest): Collection
    {
        $newsService = new FetchNewsService();
        $results = $newsService->search($searchRequest);
        $asCollection =  collect($results);
        return $asCollection->flatten();
    }
}
