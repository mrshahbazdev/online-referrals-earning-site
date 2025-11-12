<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardCommissionOnTaskCompletion
{
    public function handle(TaskCompleted $event): void
    {
        $task = $event->task;
        $user = $event->user; // Task complete karne wala user

        // **NAYA CHECK:** Commission sirf tab dein jab user ne deposit kiya ho
        if (!$user->has_deposited) {
            return; // Agar deposit nahi kiya to kuch na karein
        }

        $commissionLevels = config('referrals.commission_levels');
        $referrer = $user->referrer;

        for ($level = 1; $level <= count($commissionLevels); $level++) {
            if (!$referrer) {
                break;
            }

            $commissionRate = $commissionLevels[$level];
            $commissionAmount = $task->reward_amount * $commissionRate;

            $referrer->balance += $commissionAmount;
            $referrer->save();

            Transaction::create([
                'user_id' => $referrer->id,
                'amount' => $commissionAmount,
                'type' => 'commission',
                'description' => "Level {$level} commission from user {$user->username} for task '{$task->title}'",
            ]);

            $referrer = $referrer->referrer;
        }
    }
}
