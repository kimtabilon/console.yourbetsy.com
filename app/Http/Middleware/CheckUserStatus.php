<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Resellers;
class CheckUserStatus
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
        if (!\Request::is('/')) { 
            $reseller = Auth::guard('reseller');
            if ($reseller->check()){
                $resellerStatus = $reseller->user()->status;
                $notAllowedStatus = [2, 3, 4];
                if (in_array($resellerStatus->status, $notAllowedStatus)) {
                    Auth::guard('reseller')->logout();
                    return redirect('/');
                }
                
            }
        }
        /*  */
        
        return $next($request);
    }
}
