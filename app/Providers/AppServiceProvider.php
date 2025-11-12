<?php

namespace App\Providers;
use App\Models\InvestmentRequest;
use App\Models\CompletedTask;


use App\Observers\UserActivityObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/';
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CompletedTask::observe(UserActivityObserver::class);
        InvestmentRequest::observe(UserActivityObserver::class);
        // Aap yahan doosre models (e.g., WithdrawalRequest) bhi add kar sakte hain
    }
}
