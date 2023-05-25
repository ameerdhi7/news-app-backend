<?php

namespace App\Services\Article\Interfaces;

use Illuminate\Support\Collection;

interface NewsClientI
{
    public function getNews(): array;

    public function search(): array;

    public function mapResult(): Collection;

    public function getNewsByUserPreferences(): Collection;
}
