<?php

namespace App\Events;

use App\K3;
use Illuminate\Support\Facades\DB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class K3Baru implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $k3, $jenis;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(K3 $k3, $jenis)
    {
        $this->k3 = $k3;
        $this->jenis = $jenis;
        //
    }
    public function broadcastAs()
    {
        return 'k3-monitor';
    }
    public function broadcastWith()
    {
        $k3 = DB::table('pelapor')
            ->select('nama')
            ->where('id', $this->k3->id_pelapor)
            ->first();
        return [
            'jenis' => $this->jenis,
            'data' => $this->k3->toArray(),
            'dataPelapor' => $k3
        ];
        // return ['berhasil' => 'membuat k3'];
    }
    public function broadcastQueue()
    {
        return 'broadcastable';
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
