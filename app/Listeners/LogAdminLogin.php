<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Login;
use App\Models\AdminActivityLog;

class LogAdminLogin
{
    public function handle(Login $event): void
    {
        if ($event->user->role === 'admin') {
            AdminActivityLog::create([
                'admin_id' => $event->user->id,
                'log_type' => 'Authentication',
                'action' => 'Logged In',
                'description' => 'Admin ' . $event->user->username . ' logged in.'
            ]);
        }
    }
}
