<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserActivityLog;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        // Sirf 'user' role walon ko log karein
        if ($event->user && $event->user->role === 'user') {
            UserActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'Logged Out',
                'description' => 'User logged out successfully.'
            ]);
        }
    }
}
