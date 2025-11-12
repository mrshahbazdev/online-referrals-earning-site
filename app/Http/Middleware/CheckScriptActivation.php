<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckScriptActivation
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check karein ke script active hai ya nahi
        $isActivated = Setting::where('key', 'is_script_activated')->value('value');

        if ($isActivated === 'true') {
            // Agar active hai, to aage barhne dein
            return $next($request);
        }

        // Agar active nahi hai, to "Buy Script" wala page dikhayein
        return response(view('license.inactive'));
    }
}
