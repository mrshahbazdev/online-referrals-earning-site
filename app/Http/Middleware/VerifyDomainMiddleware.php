<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;

class VerifyDomainMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedDomain = 'earnzy.uk';

        $currentDomain = $request->getHost();

        if ($currentDomain === $allowedDomain) {
            return $next($request);
        }

        try {
            $details = [
                'title' => 'Security Alert: Unauthorized Domain Usage',
                'body' => 'Your script is being used on an unauthorized domain: ' . $currentDomain
            ];
            Mail::raw($details['body'], function($message) use ($details) {
                $message->to('mrshahbaznns@gmail.com')
                        ->subject($details['title']);
            });
        } catch (\Exception $e) {
        }

        return response(view('license.unauthorized'), 403);
    }
}
