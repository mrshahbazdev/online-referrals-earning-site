<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = Setting::pluck('value', 'key')->all();
            View::share('settings', $settings);
        }
    }
}
