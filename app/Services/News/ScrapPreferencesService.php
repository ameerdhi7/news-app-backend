<?php

namespace App\Services\News;

use App\Models\PreferenceOption;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Arr;
use Ramsey\Collection\Collection;

/**
 * Scrap preferences options to feed the preference_options table in the database
 */
class ScrapPreferencesService
{

    public function feedDatabase()
    {
        $fetchNewsService = new FetchNewsService();
        $results = [
            $fetchNewsService->getCategories(),
            $fetchNewsService->getSources(),
            $fetchNewsService->getAuthors()
        ];
        $flatten = Arr::flatten($results);
        $asCollection = collect($flatten);
        $asArray = $asCollection->toArray();
        PreferenceOption::insertOrIgnore($asArray);
    }

}
