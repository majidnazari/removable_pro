<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClanUpdated
{
    use Dispatchable, SerializesModels;

    public int $userId;
    public string $newClanId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $userId, string $newClanId)
    {
        $this->userId = $userId;
        $this->newClanId = $newClanId;
    }
}
