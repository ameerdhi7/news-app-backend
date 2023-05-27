<?php

namespace App\Services\News\Interfaces;

use App\Models\Article;
use App\Models\PreferenceOption;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

interface NewsClientI
{
    /**
     * @return Collection<Article>
     */
    public function getNews(): Collection;

    /**
     * @return array
     */
    public function search(): array;

    /**
     * @param Response $response
     * @return Collection<Article>
     */
    public function mapResult(Response $response): Collection;

    /**
     * @return Collection
     */
    public function getNewsByUserPreferences(): Collection;

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
