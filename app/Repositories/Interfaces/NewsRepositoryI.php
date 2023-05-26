<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface NewsRepositoryI
{
    public function getHomeFeed(): Collection;

}
