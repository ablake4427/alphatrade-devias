<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class P2PMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $tradeId;
    public $receiverId;
    public $html;
    public $adminHtml;
    public $message;
    public $senderId;

    public function __construct($tradeId, $receiverId, $html, $adminHtml = null, $message = null, $senderId = 0)
    {
        configBroadcasting();
        $this->tradeId    = $tradeId;
        $this->receiverId = $receiverId;
        $this->html       = $html;
        $this->adminHtml  = $adminHtml;
        $this->message    = $message;
        $this->senderId   = $senderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new PrivateChannel('p2p-message-' . $this->tradeId . "-" . $this->receiverId);
    }

    public function broadcastAs()
    {
        return 'p2p-message-' . $this->tradeId . "-" . $this->receiverId;
    }
}
