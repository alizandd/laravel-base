<?php
namespace App\Services\Implementations;



use App\Models\DailyScoreSummary;
use App\Models\User;
use App\Services\Contracts\ScoringActionFactory;
use App\Services\Contracts\ScoringActionInterface;
use Illuminate\Support\Facades\Log;

class ScoringActionFactoryImpl implements ScoringActionFactory {
    public function make(string $type): ScoringActionInterface {
        switch ($type) {
            case 'watch_video':
                return new WatchVideo();
            case 'complete_profile':
                return new CompleteProfile();
            case 'play_game':
                return new PlayGame();
            case 'upload_video':
                return new UploadVideo();
            default:
                throw new \InvalidArgumentException("Unknown scoring action type: $type");
        }
    }
}
