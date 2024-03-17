<?php

namespace App\Listeners;

use App\Events\ScoreUpdated;
use App\Services\Contracts\ScoringActionFactory;
use App\Services\Implementations\ScoringService;
use http\Client\Curl\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenScoreUpdate
{
    private $scoringActionFactory;
    private $scoringService;

    public function __construct(ScoringActionFactory $scoringActionFactory,ScoringService $scoringService) {
        $this->scoringActionFactory = $scoringActionFactory;
        $this->scoringService = $scoringService;
    }

    /**
     * Handle the event.
     */
    public function handle(ScoreUpdated $event) {
        $scoringAction = $this->scoringActionFactory->make($event->actionType);
        return $this->scoringService->addScore($scoringAction);
    }
}
