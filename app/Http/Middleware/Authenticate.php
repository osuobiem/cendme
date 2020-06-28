<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

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
                    return route('vendor.login');
                }
                // throw new HttpResponseException(response()->json(['success' => false, 'message' => "You're not logged in"],  401));
            }
        }
    }
}
