<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Resellers;
use Auth;

class ResellerController extends Controller
{
    public function index()
    {
        return view('reseller.reseller-dashboard',[
            'reseller_data' => Resellers::resellers_by_userID(Auth::guard('reseller')->id())
        ]);
    }
}
