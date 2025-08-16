<?php
// app/Helpers/RouteHelper.php

if (!function_exists('to_route')) {
    /**
     * Create a redirect response to a named route.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    function to_route($route, $parameters = [], $status = 302, $headers = [])
    {
        return redirect()->route($route, $parameters, $status, $headers);
    }
}