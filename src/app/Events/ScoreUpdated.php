<?php

namespace App\Events;

use App\Services\Implementations\ScoringService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $actionType;
    public $success = false; // Default to false, indicating the action's outcome
    /**
     * Create a new event instance.
     */
    public function __construct($actionType) {
        $this->actionType = $actionType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
