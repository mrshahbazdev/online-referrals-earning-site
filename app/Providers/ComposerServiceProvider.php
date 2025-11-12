<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Announcement;
use App\Models\Setting; // Isay import karein
use Illuminate\Support\Facades\Schema;


class ComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 'layouts.frontend' view ke saath hamesha yeh data share karein
        View::composer('layouts.frontend', function ($view) {
            $latestAnnouncement = Announcement::where('is_active', true)->latest()->first();
            $view->with('latestAnnouncement', $latestAnnouncement);

            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->all();
                $view->with('settings', $settings);
            }
        });
    }
}
