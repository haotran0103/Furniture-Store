<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;
class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('customer')->check() && $request->isMethod('get')){
            UserActivityLog::create([
                'user_id' => Auth::guard('customer')->id(),
                'action' => $request->getMethod() . ' ' . $request->url(),
                'details' => 'IP: ' . $request->ip() . ', User-Agent: ' . $request->header('User-Agent'),
            ]);
        }
        
        return $next($request);
        
    }
}
