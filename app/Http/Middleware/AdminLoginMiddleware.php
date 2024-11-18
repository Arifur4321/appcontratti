<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the admin is logged in and if the session is still valid
        $loginTime = $request->session()->get('admin_login_time');
        $now = Carbon::now();

        if ($loginTime && $now->diffInMinutes($loginTime) < 1) {
            return $next($request);
        }

        // If not logged in or session has expired, forget the session and redirect
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_login_time');
        return redirect()->route('admin.login');
    }
}
