<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardJoiningBonus
{
    public function handle(Registered $event): void
    {
        // $newUser = $event->user;
        // $bonusLevels = config('referrals.joining_bonus_levels', []);
        // $referrer = $newUser->referrer; // Level 1 upline

        // for ($level = 1; $level <= count($bonusLevels); $level++) {
        //     if (!$referrer) {
        //         break; // Agar koi referrer nahi hai to ruk jayein
        //     }

        //     $bonusAmount = $bonusLevels[$level] ?? 0;

        //     if ($bonusAmount > 0) {
        //         // Referrer ka balance update karein
        //         $referrer->balance += $bonusAmount;
        //         $referrer->save();

        //         // Referrer ke liye transaction record banayein
        //         Transaction::create([
        //             'user_id' => $referrer->id,
        //             'amount' => $bonusAmount,
        //             'type' => 'commission',
        //             'description' => "Level {$level} joining bonus for referring user: " . $newUser->username,
        //         ]);
        //     }

        //     // Agle level ke referrer ko dhoondein
        //     $referrer = $referrer->referrer;
        // }
    }
}
