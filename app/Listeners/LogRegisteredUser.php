<?php
namespace App\Listeners;
use Illuminate\Auth\Events\Registered;
use App\Models\AdminActivityLog;

class LogRegisteredUser
{
    public function handle(Registered $event): void
    {
        if ($event->user->role === 'admin') {
            AdminActivityLog::create([
                'admin_id' => $event->user->id,
                'log_type' => 'Authentication',
                'action' => 'Admin Registered',
                'description' => 'A new admin account was created: ' . $event->user->username
            ]);
        }
    }
}
