<?php

namespace App\Services\News;

use App\Models\PreferenceOption;
use Illuminate\Support\Arr;

/**
 * Scrap preferences options to feed the preference_options table in the database
 */
class ScrapPreferencesService
{

    public function feedDatabase(): void
    {
        $fetchNewsService = new FetchNewsService();
        //get and combine results
        $results = [
            $fetchNewsService->getCategories(),
            $fetchNewsService->getSources(),
//            $fetchNewsService->getAuthors()
        ];

        $flatten = Arr::flatten($results);
        $asCollection = collect($flatten);
        $asArray = $asCollection->toArray();
        //insert or already exists records
        PreferenceOption::insertOrIgnore($asArray);
    }

}
