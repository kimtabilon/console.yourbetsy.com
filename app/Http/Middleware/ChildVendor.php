<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use App\Resellers;

class ChildVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth_reseller = Auth::guard('reseller');
        if ($auth_reseller->check()){
            $res_profile = $auth_reseller->user()->profile;
            if ($res_profile->reseller_position == 1) {
                // dd('none');
                return redirect()->route('vendor.dashboard');
            }else{
                
            }
        }
        return $next($request);
    }
}
