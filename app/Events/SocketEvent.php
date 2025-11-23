<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Game;

class SocketEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $broadcast_with=[];
    public string $broadcast_on='';
    public string $broadcast_as='';

    public function __construct(public array $data)
    {
        $this->broadcast_with=$data['broadcast_with'];
        $this->broadcast_on=$data['broadcast_on'];
        $this->broadcast_as=$data['broadcast_as'];
    }

    public function broadcastOn(): Channel
    {
        return new Channel($this->broadcast_on);
    }

    // Only send what we need — super small payload
    public function broadcastWith(): array
    {
        return $this->broadcast_with;
    }

    public function broadcastAs(): string
    {
        return $this->broadcast_as;
    }
}
