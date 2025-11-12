<?php

namespace App\Observers;

use App\Models\CompletedTask;
use App\Models\InvestmentRequest;
use App\Models\UserActivityLog;

class UserActivityObserver
{
    public function created(object $model): void
    {
        $action = '';
        $description = '';

        // Check karein ke konsa model create hua hai
        if ($model instanceof CompletedTask) {
            $action = 'Task Completed';
            $description = 'User completed task ID: ' . $model->task_id;
        } elseif ($model instanceof InvestmentRequest) {
            $action = 'Investment Request';
            $description = 'User requested an investment of $' . $model->amount;
        }
        // Aap yahan WithdrawalRequest aur doosre models ke liye bhi logic add kar sakte hain

        if ($action && isset($model->user_id)) {
            UserActivityLog::create([
                'user_id' => $model->user_id,
                'action' => $action,
                'description' => $description,
            ]);
        }
    }
}
