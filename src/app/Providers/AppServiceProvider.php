<?php

namespace App\Providers;

use App\Services\Contracts\TokenInterface;
use App\Services\Implementations\TokenService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\NotifierInterface;
use App\Services\Implementations\SmsNotifier;
use App\Services\Contracts\OtpInterface;
use App\Services\Implementations\OtpService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotifierInterface::class, SmsNotifier::class);
        $this->app->bind(OtpInterface::class, OtpService::class);
        $this->app->bind(TokenInterface::class, TokenService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
