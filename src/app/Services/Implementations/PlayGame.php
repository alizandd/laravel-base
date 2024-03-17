<?php
namespace App\Services\Implementations;

use App\Services\Contracts\GamePlayingInterface ;


class PlayGame implements GamePlayingInterface  {
    public function score(): int {
        return 10; // Points for watching a video
    }
}
