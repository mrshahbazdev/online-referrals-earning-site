<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Logout;
use App\Models\AdminActivityLog;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user && $event->user->role === 'admin') {
            AdminActivityLog::create([
                'admin_id' => $event->user->id,
                'log_type' => 'Authentication',
                'action' => 'Logged Out',
                'description' => 'Admin ' . $event->user->username . ' logged out.'
            ]);
        }
    }
}
