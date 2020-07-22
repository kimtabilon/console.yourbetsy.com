<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Mail\VerifyResellers;
use Illuminate\Support\Facades\Mail;

use App\Resellers;
use App\ResellersProfiles;
use App\ResellersAddresses;
use App\ResellersEmailAddresses;
use App\ResellersMobileNumbers;
/* use App\ResellersLandlineNumbers; */
use App\ResellersStatuses;
use App\ResellersSecondaryNumbers;

class SignUpController extends Controller
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
        return view('signup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $messages = [
            'required' => 'Required',
            // 'unique' => 'Unique'
        ];

        // $required_validation = request()->validate([
        //     'username' => ['required','unique:resellers,username'],
        //     'password' => 'required',
        //     'security_question' => 'required',
        //     'security_answer' => 'required',
        //     'reseller_name' => ['required','unique:resellers_profiles,reseller_name'],
        //     'contact_person' => ['required','unique:resellers_profiles,contact_person'],
        //     'address' => 'required',
        //     'email_address' => ['required','unique:resellers_email_addresses,email_address'],
        //     'landline_number' => ['required','unique:resellers_landline_numbers,landline_number'],
        //     'mobile_number' => ['required','unique:resellers_mobile_numbers,mobile_number']
        // ], $messages);
        
        $already_member;
        if (request('already_member') == "on") {
            $validate_data = [
                'username' => ['required','unique:resellers,username'],
                'password' => 'required|confirmed|min:6',
                'security_question' => 'required',
                'security_answer' => 'required',
                'seller_name' => ['required','unique:resellers_profiles,reseller_name'],
                'contact_person' => ['required'],
                'address' => 'required',
                'email_address' => ['required'],
                'mobile_number' => ['required']
            ];
            $already_member = 0;
        }else{
            $validate_data = [
                'username' => ['required','unique:resellers,username'],
                'password' => 'required|confirmed|min:6',
                'security_question' => 'required',
                'security_answer' => 'required',
                'seller_name' => ['required','unique:resellers_profiles,reseller_name'],
                'contact_person' => ['required','unique:resellers_profiles,contact_person'],
                'address' => 'required',
                'email_address' => ['required','unique:resellers_email_addresses,email_address'],
                /* 'landline_number' => ['required','unique:resellers_landline_numbers,landline_number'], */
                'mobile_number' => ['required','unique:resellers_mobile_numbers,mobile_number']
            ];
            $already_member = 1;
        }
        
        if (request('reseller_type') == 1) {
            $validate_data = array_merge($validate_data,['business_permit_number' => ['required','unique:resellers_profiles,business_permit_number']]);
        }
        $required_validation = request()->validate( $validate_data, $messages);

        $resellers = new Resellers();
        $resellers->username = request('username');
        $resellers->password = Hash::make(request('password'));

        $array_sec_question = array(
            "question" => request('security_question'),
            "answer" => request('security_answer')
        );
        $resellers->security_question = json_encode($array_sec_question);
        $resellers->save();

        $profiles = new ResellersProfiles();
        $profiles->reseller_name = ucwords(request('seller_name'));
        $profiles->contact_person = ucwords(request('contact_person'));
        $profiles->reseller_type = request('reseller_type');
        $profiles->already_member = $already_member;
        $profiles->ip = \Request::ip();
        if (request('reseller_type') == 1) {
            $profiles->business_permit_number = request('business_permit_number');
        }
        $profiles->username_id =  $resellers->id;
        $profiles->save();

        $address = New ResellersAddresses();
        $address->address = request('address');
        $address->username_id =  $resellers->id;
        $address->save();

        $email_address = New ResellersEmailAddresses();
        $email_address->email_address = request('email_address');
        $email_address->username_id =  $resellers->id;
        $email_address->save();

        /* $landline_number = New ResellersLandlineNumbers();
        $landline_number->landline_number = request('landline_number');
        $landline_number->username_id =  $resellers->id;
        $landline_number->save(); */

        $mobile_number = New ResellersMobileNumbers();
        $mobile_number->mobile_number = request('mobile_number');
        $mobile_number->username_id =  $resellers->id;
        $mobile_number->save();

        if (!empty(request('secondary_contact_number'))) {
            $secondary_number = new ResellersSecondaryNumbers;
            $secondary_number->secondary_number = request('secondary_contact_number');
            $secondary_number->username_id =  $resellers->id;
            $secondary_number->save();
        }
       

        $status = New ResellersStatuses();
        $status->username_id =  $resellers->id;
        $status->save();

        $profiles->action_type = 101;
        mail::to($email_address->email_address)->send(new VerifyResellers($profiles));

        // return redirect('signup/login')->with( ['from_signup' => 'TEST'] );
        return redirect('signup/success');
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
