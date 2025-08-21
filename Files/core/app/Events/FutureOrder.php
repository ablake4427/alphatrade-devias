<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FutureOrder implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $pairSym;
    
    /**
     * Create a new event instance.
     */
    public function __construct($order, $pairSym)
    {
        configBroadcasting();
        $this->order   = $order;
        $this->pairSym = $pairSym;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('future-order-placed-to-' . $this->pairSym),
        ];
    }

    public function broadcastAs()
    {
        return 'future-order-placed-to-' . $this->pairSym;
    }
}
