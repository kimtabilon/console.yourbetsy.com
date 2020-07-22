<?php

namespace App\Http\Controllers\Reseller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;

use Auth;
use App\Mail\VerifyResellers;

use App\Resellers;
use App\ResellersProfiles;
use App\ResellersAddresses;
use App\ResellersEmailAddresses;
use App\ResellersMobileNumbers;
use App\ResellersStatuses;

class ResellerChildManagementController extends Controller
{
    public function __construct() {
        $this->middleware('child_vendor',['except' => ['add_child','child_list']]);
    }

    public function child_list() {

        /* $data = ResellersProfiles::with(['status','reseller'])
                                    ->where([["resellers_profiles.parent",Auth::user()->id],["resellers_statuses",0]])
                                    ->get(); */
        /* dd(Auth::user()); */
        $data_active = ResellersProfiles::whereHas("status", 
            function(Builder $q){
                $q->where("resellers_statuses.status","0");
            }
        )
        ->with(['reseller','status'])
        ->where("resellers_profiles.parent",Auth::user()->id)->get();

        $data_inactive = ResellersProfiles::whereHas("status", 
            function(Builder $q){
                $q->whereIn("resellers_statuses.status", [4,3]);
            }
        )
        ->with(['reseller','status'])
        ->where("resellers_profiles.parent",Auth::user()->id)->get();

        return view('reseller.reseller-child-list',[
                    'child_active' => $data_active,
                    'child_inactive' => $data_inactive,

                ]);
    }

    public function add_child() {
        /* $profiles = new ResellersProfiles();
        $profiles->reseller_name = ucwords(request('vendor_name'));

        $email_address = New ResellersEmailAddresses();
        $email_address->email_address = request('email_address');

        $profiles->action_type = 300;
        $profiles->parent = Auth::user()->profile->reseller_name;
        mail::to($email_address->email_address)->send(new VerifyResellers($profiles));
        if (mail::failures()) {
            return response()->json(
                ['status' => "FAILED"]
            , 200);
        }else{
            return response()->json(
                ['status' => "Sent"]
            , 200);
        } */
        
        $validate_data = [
            'username' => ['required','unique:resellers,username'],
            'password' => 'required|confirmed|min:6',
            'security_question' => 'required',
            'security_answer' => 'required',
            'vendor_name' => ['required','unique:resellers_profiles,reseller_name'],
            'contact_person' => ['required','unique:resellers_profiles,contact_person'],
            'address' => 'required',
            'email_address' => ['required','unique:resellers_email_addresses,email_address'],
            'mobile_number' => ['required','unique:resellers_mobile_numbers,mobile_number']
        ];

        $required_validation = Validator::make(request()->all(),$validate_data);
        if ($required_validation->fails()) {    
            $errors = $required_validation->messages();
            $status = "error";
        }else {

            $saved_counter = 0;
            $resellers = new Resellers();
            $resellers->username = request('username');
            $resellers->password = Hash::make(request('password'));

            $array_sec_question = array(
                "question" => request('security_question'),
                "answer" => request('security_answer')
            );
            $resellers->security_question = json_encode($array_sec_question);
            $reseller_saved = $resellers->save();

            $profiles = new ResellersProfiles();
            $profiles->reseller_name = ucwords(request('vendor_name'));
            $profiles->contact_person = ucwords(request('contact_person'));
            $profiles->reseller_type = 0;
            $profiles->already_member = 0;
            $profiles->reseller_position = 1;
            $profiles->parent = Auth::user()->id;
            $profiles->ip = \Request::ip();
            $profiles->username_id =  $resellers->id;
            $profiles_saved = $profiles->save();

            $address = New ResellersAddresses();
            $address->address = request('address');
            $address->username_id =  $resellers->id;
            $address_saved = $address->save();

            $email_address = New ResellersEmailAddresses();
            $email_address->email_address = request('email_address');
            $email_address->username_id =  $resellers->id;
            $email_address_saved = $email_address->save();

            $mobile_number = New ResellersMobileNumbers();
            $mobile_number->mobile_number = request('mobile_number');
            $mobile_number->username_id =  $resellers->id;
            $mobile_number_saved = $mobile_number->save();

            $status = New ResellersStatuses();
            $status->username_id =  $resellers->id;
            $status->status =  0;
            $status_saved = $status->save();

            if (!$reseller_saved ||
            !$profiles_saved ||
            !$address_saved ||
            !$email_address_saved ||
            !$mobile_number_saved ||
            !$status_saved) {
                $saved_counter++;
            }

            if ($saved_counter == 0) {
                $status = "sucess";

                $profiles->action_type = 300;
                $profiles->parent = Auth::user()->profile->reseller_name;
                mail::to($email_address->email_address)->send(new VerifyResellers($profiles));
            }else{
                $status = "unsucess";
            }
            
            $errors = [];


        }
        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function view_child_details() {
        return response()->json(
            Resellers::resellers_by_userID(request('id'))
        , 200);
    }

    public function child_change_status() {
        // dd(request('status'));
        $reseller_status = ResellersStatuses::find(request('verify_reseller_id'));
        $reseller_status->status = request('status');
        $saved = $reseller_status->save();

        if(!$saved){
            $status = "unsuccessful";
        }else{
            $status = "successful";
            
            $reseller_profile = ResellersProfiles::where('username_id',$reseller_status->username_id)->first();
            $reseller_profile->action_type = request('status');
            mail::to($reseller_profile->emailaddress->email_address)->send(new VerifyResellers($reseller_profile));
        }
        return response()->json(
            ['status' => $status]
        , 200);
    }
}
