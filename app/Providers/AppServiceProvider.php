<?php

namespace App\Providers;

use App\Repositories\Interfaces\NewsRepositoryI;
use App\Repositories\NewsRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //binding news repository
        $this->app->bind(NewsRepositoryI::class, NewsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
