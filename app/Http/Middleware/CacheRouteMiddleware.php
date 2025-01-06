<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class CacheRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        if ($this->shouldCacheRoute($routeName)) {
            $cacheKey = 'route:' . $routeName;

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = $next($request);

            Cache::put($cacheKey, $response, 60); // Cache the response for 60 minutes

            return $response;
        }

        return $next($request);
    }

    private function shouldCacheRoute($routeName)
    {
        // Define your logic to determine if the route should be cached
        // For example, you can check if it's a specific route or based on certain conditions

        return $routeName === 'your.route.name';
    }
}
