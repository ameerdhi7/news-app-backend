<?php

namespace App\Repositories;

use App\Http\Requests\SearchRequest;
use App\Models\PreferenceOption;
use App\Models\User;
use App\Repositories\Interfaces\NewsRepositoryI;
use App\Services\News\FetchNewsService;
use Illuminate\Contracts\Auth\Authenticatable;
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
        $fields = ["id", "name", "type"];
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
        $asCollection = collect($results);
        return $asCollection->flatten();
    }

    /**
     * @param Authenticatable $user
     * @param array $preferences
     * @return void
     */

    public function savePreferences(Authenticatable $user, array $preferences)
    {
        // Retrieve the user's current preferences
        $currentPreferences = $user->preferences()->pluck('preference_option_id')->toArray();

        // Determine the preferences to attach and detach
        $newPreferences = $this->mapPreferences($preferences);
        $preferencesToAttach = array_diff($newPreferences, $currentPreferences);
        $preferencesToDetach = array_diff($currentPreferences, $newPreferences);

        // Sync the preferences
        $user->preferences()->attach($preferencesToAttach);
        $user->preferences()->detach($preferencesToDetach);
    }

    private function mapPreferences(array $preferences)
    {
        $mappedPreferences = [];

        foreach ($preferences as $type => $values) {
            foreach ($values as $value) {
                $mappedPreferences[] = $value;
            }
        }

        return $mappedPreferences;
    }

}
