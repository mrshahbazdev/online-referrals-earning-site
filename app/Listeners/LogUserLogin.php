<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserActivityLog;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        // Sirf 'user' role walon ko log karein
        if ($event->user->role === 'user') {
            UserActivityLog::create([
                'user_id' => $event->user->id,
                'action' => 'Logged In',
                'description' => 'User logged in successfully.'
            ]);
        }
    }
}
