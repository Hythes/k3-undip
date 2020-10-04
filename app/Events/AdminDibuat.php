<?php

namespace App\Events;

use App\Admin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminDibuat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
        //
    }

    public function broadcastWith()
    {
        return [
            'id'       => $this->admin->id,
            'name'     => $this->admin->nama,
            'username' => $this->admin->username,
        ];
    }
    public function broadcastQueue()
    {
        return 'broadcastable';
    }
    public function broadcastAs()
    {
        return 'admin-monitor';
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('admin');
    }
}
