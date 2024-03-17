<?php
namespace App\Services\Implementations;

use App\Services\Contracts\VideoWatchingInterface;


class WatchVideo implements VideoWatchingInterface {
    public function score(): int {
        return 10; // Points for watching a video
    }
}
