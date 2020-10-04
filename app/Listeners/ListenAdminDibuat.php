<?php

namespace App\Listeners;

use App\Events\AdminDibuat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ListenAdminDibuat implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AdminDibuat $event)
    {
        Log::info('Berhasil membuat Admin dengan nama : ' . $event->admin->nama);
    }
}
