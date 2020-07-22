<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Resellers;

use Illuminate\Support\Facades\DB;
use Redirect;

class ResellerController extends Controller
{

    public function __contruct() {
        // $this->middleware('guest:reseller',['except' => ['logout']]);
        $this->middleware('guest:reseller')->except('logout');
    }

    public function showLoginForm() {
        return view('auth.reseller');
    }

    public function login(Request $request) {
        // Validate the form data
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);

        // Attempt to log the uesr in
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::guard('reseller')->attempt($credentials, $request->remember)) {
            // if successful, then redirect to their intended location

            $resellers = DB::table('resellers')
                ->select('id')
                ->where('username', $request->username)
                ->first();
            $resellers_status = DB::table('resellers_statuses')
                ->where('username_id',$resellers->id)
                ->first();
            // echo json_encode($resellers->id);
            // dd($resellers_status);
            // die;

            if ($resellers_status->status == 0) {
                return redirect()->intended(route('vendor.dashboard'));
            // }elseif($resellers_status->status == 1) {
            }else{
                // return redirect();
                return Redirect::back()->withErrors(['notallowed']);
            }
            
        }
        
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('username', 'remember'))->withErrors(['incorrect']);

    }

    public function logout() {
        Auth::guard('reseller')->logout();
        return redirect('/');
    }
}
