<?php

namespace App\Listeners;

use App\Events\DataBaru;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiDataBaru implements ShouldQueue
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
     * @param  DataBaru  $event
     * @return void
     */
    public function handle(DataBaru $event)
    {
        Log::info('Berhasil membuat data baru!');
    }
}
