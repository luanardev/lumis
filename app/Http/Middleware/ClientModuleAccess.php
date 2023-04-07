<?php

namespace App\Http\Middleware;

use App\Exceptions\DisabledModuleException;
use App\Models\App;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $module = null, $guard = null)
    {
        if (!empty($module)) {
            $system = App::findByName($module);

            if (empty($system)) {
                return back();
            }

            app()->instance('module', $system->display_name);

            if ($system->disabled()) {
                throw new DisabledModuleException;
            }

        } else {
            app()->instance('module', config('app.name'));
        }


        return $next($request);

    }
}
