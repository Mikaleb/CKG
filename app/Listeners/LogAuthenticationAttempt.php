<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Support\Facades\Log;

class LogAuthenticationAttempt
{
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
     * @param Attempting $event
     *
     * @return void
     */
    public function handle(Attempting $event)
    {
        $ip = geoip()->getClientIP();
        Log::notice('IP ' . $ip . ' tried to connect');
    }
}
