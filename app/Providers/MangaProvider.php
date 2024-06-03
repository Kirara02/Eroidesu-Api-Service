<?php

namespace App\Providers;

use App\Interface\MangaInterface;
use App\Repository\MangaRepository;
use Illuminate\Support\ServiceProvider;

class MangaProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MangaInterface::class,MangaRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
