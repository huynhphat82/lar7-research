<?php

namespace App\Http\Middleware;

use Closure;

class SessionTimeout
{
    /**
     * Session timeout
     *
     * @var int
     */
    private $timeout = 1*60*60; // 1h

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->timeout = config('session.lifetime') ?: $this->timeout;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session('lastActivityTime')) {
            session()->put('lastActivityTime', time());
        }
        // expired
        else if (time() - session('lastActivityTime') > $this->timeout) {
            // Destroy session
            session()->forget('lastActivityTime');
            // Logout
            auth()->guard('guest')->logout();
            // Redirect to login page
            return redirect()->route('admin.logout');
        }

        $isLoggedIn = $request->path() != '';
        $isLoggedIn
            ? session()->put('lastActivityTime', time())
            : session()->forget('lastActivityTime');

        return $next($request);
    }
}
