<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\ModuleAccessException;
use App\Exceptions\DisabledModuleException;
use App\Models\System;

class ModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $app, $guard = null)
    {
        $system = System::findByName($app);

        if(empty($system)){
            return back();
        }

        if($system->disabled()){
            throw new DisabledModuleException;
        }

        if(!auth()->user()->hasApp($app) ){
            throw new ModuleAccessException;
        }

        
        return $next($request);
               
    }
}
