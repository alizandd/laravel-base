<?php

namespace App\Providers;

use App\Services\Contracts\GamePlayingInterface;
use App\Services\Contracts\ProfileCompletionInterface;
use App\Services\Contracts\ScoringActionFactory;
use App\Services\Contracts\VideoWatchingInterface;
use App\Services\Implementations\CompleteProfile;
use App\Services\Implementations\PlayGame;
use App\Services\Implementations\ScoringActionFactoryImpl;
use App\Services\Implementations\ScoringService;
use App\Services\Implementations\WatchVideo;
use Illuminate\Support\ServiceProvider;

class ScoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VideoWatchingInterface::class, WatchVideo::class);
        $this->app->bind(ProfileCompletionInterface::class, CompleteProfile::class);
        $this->app->bind(GamePlayingInterface::class, PlayGame::class);

        // Binding the ScoringService with automatic dependency injection
        $this->app->singleton(ScoringService::class, function ($app) {
            return new ScoringService(auth()->user());
        });
        $this->app->bind(ScoringActionFactory::class, ScoringActionFactoryImpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
