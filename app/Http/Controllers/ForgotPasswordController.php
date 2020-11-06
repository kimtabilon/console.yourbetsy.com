<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\VerifyResellers;
use Illuminate\Support\Facades\Mail;
use Auth;

use App\Resellers;
use App\ResellersProfiles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Redirect;

class ForgotPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('signup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.passwords.reset');
    }

    public function verifyinfo(Request $request) {
        // Validate the form data
        $this->validate($request,[
            'email' => 'required',
            'security_question' => 'required',
            'security_answer' => 'required',
        ]);

        // Attempt to log the uesr in
        $credentials = [
            'email' => $request->email,
            'security_question' => $request->security_question,
            'security_answer' => $request->security_answer,
        ];



            $resellers = DB::table('resellers_email_addresses')
                ->select(array('resellers.security_question','resellers.id','resellers_profiles.reseller_name'))
                ->where('email_address', $request->email)
                ->join('resellers', 'resellers.id', '=', 'resellers_email_addresses.username_id')
                ->join('resellers_profiles', 'resellers_profiles.id', '=', 'resellers.id')
                ->first();
            
        if ($resellers) { 
            $question_json = json_decode($resellers->security_question);

                if($question_json->question == $request->security_question && $question_json->answer == $request->security_answer ){
                    

                    if(!isset($request->password)){
                        return Redirect::back()->with('input_pass','input_pass')
                                            ->with('email', $request->email)
                                            ->with('security_question', $request->security_question)
                                            ->with('security_answer', $request->security_answer);

                    }else{


                                if($request->password == $request->password_confirmation){

                                    if(strlen($request->password) >= 6 ){                                    
                                        DB::table('resellers')
                                        ->where('id', $resellers->id)
                                        ->update(['password' => Hash::make($request->password)]);
                                        $profiles = new ResellersProfiles();
                                        $profiles->reseller_name = ucwords($resellers->reseller_name);
                                        $profiles->action_type = 500;
                                        mail::to($request->email)->send(new VerifyResellers($profiles));
                                        return view('auth.passwords.reset-success');
                                    }else{
                                        return Redirect::back()->withErrors(['insuff_password'])
                                        ->with('repassword','repassword')
                                        ->with('email', $request->email)
                                        ->with('security_question', $request->security_question)
                                        ->with('security_answer', $request->security_answer);
                                    }

                                }else{
                                    return Redirect::back()->withErrors(['wrong_password'])
                                    ->with('repassword','repassword')
                                    ->with('email', $request->email)
                                    ->with('security_question', $request->security_question)
                                    ->with('security_answer', $request->security_answer);
                                }
                        

                    }




                }else{
                    return Redirect::back()->withErrors(['wrong_question']);
                }

        }else{
            return Redirect::back()->withErrors(['no_email']);
        }
        
    }

    public function signupsuccess()
    {
        return view('signup.signup-success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
