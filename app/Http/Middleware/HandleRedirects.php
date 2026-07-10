<?php

namespace App\Http\Middleware;

use App\Models\BdgsRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            $path = '/'.ltrim($request->path(), '/');
            if ($path !== '/') {
                $path = rtrim($path, '/').'/';
            }

            $redirect = BdgsRedirect::query()
                ->where('is_active', true)
                ->where(function ($q) use ($path, $request) {
                    $q->where('from_url', $path)
                        ->orWhere('from_url', '/'.ltrim($request->path(), '/'));
                })
                ->first();

            if ($redirect) {
                return redirect($redirect->to_url, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
