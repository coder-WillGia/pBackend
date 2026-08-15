<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Categories\Repositories\CategoryRepositoryInterface::class,
            \App\Infrastructure\Categories\Persistence\Eloquent\Repositories\EloquentCategoryRepository::class
        );

        $this->app->bind(
            \App\Domain\Products\Repositories\ProductRepositoryInterface::class,
            \App\Infrastructure\Products\Persistence\Eloquent\Repositories\EloquentProductRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
