<?php

namespace App\Repositories;

use App\Http\Requests\SearchRequest;
use App\Models\PreferenceOption;
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
        $asCollection = $newsService->getNews();
        return $asCollection->flatten();
    }

    /**
     * @return Collection
     */

    public function getPreferencesOptions(): Collection
    {
        $fields = ["id", "name", "type"];

        // Retrieve all categories
        $allCategories = PreferenceOption::select($fields)
            ->where("type", "category")
            ->get();

        // Retrieve 20 authors and 20 sources
        $authors = PreferenceOption::select($fields)
            ->where("type", "author")
            ->take(20)
            ->get();
        $sources = PreferenceOption::select($fields)
            ->where("type", "source")
            ->take(20)
            ->get();

        // Merge the results into a single array
        $mergedResults = array_merge(
            $allCategories->toArray(),
            $authors->toArray(),
            $sources->toArray(),
        );

        // Convert the merged results into a collection
        $asCollection = collect($mergedResults);

        // Retrieve the user's preferences
        $userPreferences = auth()->user()->preferences()->get();

        // Merge the options collection with user preferences
        $mergedCollection = $asCollection->map(function ($option) use ($userPreferences) {
            $matchingPreference = $userPreferences->where('id', $option['id'])->first();
            $option['checked'] = !is_null($matchingPreference);
            return $option;
        });

        // Group the merged collection by the 'type' field
        $processedGroupedByType = $mergedCollection->groupBy("type");

        // Return the processed collection grouped by type
        return $processedGroupedByType;
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

    public function getNewsByPreferences(Collection $preferences): Collection
    {
        $newsService = new FetchNewsService();
        $newByPreferences = $newsService->getNewsByPreferences($preferences);
        return $newByPreferences->flatten();
    }

}
