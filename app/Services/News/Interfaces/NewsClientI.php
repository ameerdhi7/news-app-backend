<?php

namespace App\Services\News\Interfaces;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

interface NewsClientI
{
    public function getNews(): Collection;

    public function search(): array;

    public function mapResult(Response $response): Collection;

    public function getNewsByUserPreferences(): Collection;
}
