<?php

namespace App\Providers;

use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

/**
 * Class ApiClientServiceProvider
 */
class ApiClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NYTimesHttpClient::class, function () {
            return new NYTimesHttpClient(
                (bool) config('services.books.providers.nytimes.cache.enabled'),
                (int) config('services.books.providers.nytimes.cache.ttl'),
                Http::baseUrl(config('services.books.providers.nytimes.base_url'))
                    ->acceptJson()
                    ->retry(3, 100)
                    ->withQueryParameters(['api-key' => config('services.books.providers.nytimes.api_key')])
            );
        });
    }
}
