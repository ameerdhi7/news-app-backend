<?php

namespace App\Services\News\Interfaces;

use App\Http\Requests\SearchRequest;
use App\Models\Article;
use App\Models\PreferenceOption;
use Illuminate\Support\Collection;

interface NewsClientI
{
    /**
     * @return Collection<Article>
     */
    public function getNews(): Collection;

    /**
     * @param SearchRequest
     */
    public function search(SearchRequest $searchRequest);

    /**
     * @param array $articles
     * @return Collection<Article>
     */
    public function mapResult(array $articles): Collection;

    /**
     * @return Collection|null
     */
    public function getByPreferences(Collection $preferences);

    /**
     * @return array<PreferenceOption>
     */
    public function getCategories(): array;

    /**
     * @return array<PreferenceOption>
     */

    public function getSources(): array;

    /**
     * @return array<PreferenceOption>
     */
    public function getAuthors(): array;
}
