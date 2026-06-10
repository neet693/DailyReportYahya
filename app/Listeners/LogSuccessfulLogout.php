<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        LoginLog::where('user_id', $event->user->id)
            ->whereNull('logout_at')
            ->latest()
            ->first()
            ?->update([
                'logout_at' => now(),
            ]);
    }
}
