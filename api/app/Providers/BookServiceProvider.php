<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use App\Services\Book\BookFetcherService;
use App\Services\Book\NYTimes\NYTimesBookProvider;
use Illuminate\Support\ServiceProvider;

class BookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Can be added more providers in the future
        $this->app->singleton(BookFetcherService::class, function ($app) {
            return new BookFetcherService(
                [
                    NYTimesBookProvider::class => new NYTimesBookProvider($app->make(NYTimesHttpClient::class)),
                ]
            );
        });
    }
}
