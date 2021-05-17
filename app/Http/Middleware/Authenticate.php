<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (!$request->expectsJson()) {
            if ($request->is('api/*')) {
                throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Auth token required!'],  401));
            } else {

                if ($request->is('vendor') || $request->is('vendor/*')) {
                    return url('vendor/login');
                } elseif ($request->is('admin') || $request->is('admin/*')) {
                    return url('admin/login');
                }
            }
        }
    }
}
