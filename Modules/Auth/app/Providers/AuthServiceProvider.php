<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Interfaces\AuthRepositoryInterface;
use Modules\Auth\Repositories\AuthRepository;
use Modules\Auth\Providers\RouteServiceProvider;
use Modules\Auth\Providers\EventServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
