<?php

namespace App\Events;

use App\Models\Order_banktransfer;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BankTransfer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $touser;
    protected $banktransfer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $touser, Order_banktransfer $order_banktransfer)
    {
        $this->touser = $touser;
        $this->banktransfer = $order_banktransfer;
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return ['bank-transfer'];
    }
}
