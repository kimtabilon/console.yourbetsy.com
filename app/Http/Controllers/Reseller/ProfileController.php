<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Storage;
use Illuminate\Support\Facades\Validator;

use App\Resellers;
use App\ResellersProfileRequests;
use App\ResellersAboutUses;
use App\ResellersShippingPolicies;
use App\ResellersReturnPolicies;
use App\ResellersPaymentInformations;
use Auth;

class ProfileController extends Controller
{

    /* public function __construct()
    {

        $this->beforeFilter(function() {
            $res_profile = Auth::user()->profile;
            if ($res_profile->reseller_position == 0) {
                return redirect()->route('reseller.dashboard');
                dd("TEST");
            }
        });
    } */

    public function __construct()
    {
        // $this->middleware('child_vendor', ['only' => ['index']]);

        // $this->middleware('child_vendor',['except' => ['updateprofile']]);
    }
    public function index() {
        
        if (Auth::guard('reseller')->check()){
            $reseller = Auth::user();
            $res_profile = Auth::user()->profile;
            $email_add = Auth::user()->email_address;
            $mobile_no = Auth::user()->mobile_numbers;
            $address = Auth::user()->address;
            $secondary_no = Auth::user()->secondary_contact_numbers;

            $reseller->email_address = $email_add;
            $reseller->profile_details = $res_profile;
            $reseller->mobile_no = $mobile_no;
            $reseller->address = $address;
            $reseller->secondary_no = $secondary_no;
            $reseller->profile_img = getProfilePhoto($reseller->id);
            $reseller->banner_img = getBannerPhoto($reseller->id);
        }
        // dd($reseller->secondary_no);
        return view('reseller.reseller-profile-update',
        ['data' =>$reseller]
        );
    }

    public function updateprofile() {
        /* dd(request()->all()); */
        // dd(request('primary_contact_number'));

        /* $required_validation = request()->validate([
                'email_address' => [
                    Rule::exists('resellers_email_addresses')->where(function ($query) {
                        $query->where('username_id','!=', request('username_id') );
                    }),
                ], 
        ]); */
        if (request('edited_content') > 0) {
            $check_pending_req = ResellersProfileRequests::where([["username_id", request('username_id')],["status",'1']])->first();
        }else{
            $check_pending_req = false;
        }
        
        if ($check_pending_req) {
            return response()->json(["status" => "not allowed"]);
        }else {
            $validate_email_add = [];
            $validate_address = [];
            $validate_contact_person = [];
            $validate_primary_contact_number = [];
            $secondary_primary_contact_number = [];
            
            $requested_data = [];
            $errors = [];

            if (request()->has('email_address')) {
                $validate_email_add = ['email_address' => 'unique:resellers_email_addresses,email_address,'.request('username_id').",username_id"];
                $requested_data[] = [
                    "column" => "email_address",
                    "column_id" => request('email_address_id'),
                    "value" => request('email_address')
                ];
            }

            if (request()->has('contact_person')) {
                $validate_contact_person = ['contact_person' => 'unique:resellers_profiles,contact_person,'.request('username_id').",username_id"];
                $requested_data[] = [
                    "column" => "contact_person",
                    "column_id" => request('contact_person_id'),
                    "value" => request('contact_person')
                ];
            }

            if (request()->has('primary_contact_number')) {
                $validate_primary_contact_number = ['primary_contact_number' => 'unique:resellers_mobile_numbers,mobile_number,'.request('username_id').",username_id"];
                $requested_data[] = [
                    "column" => "primary_contact_number",
                    "column_id" => request('primary_contact_number_id'),
                    "value" => request('primary_contact_number')
                ];
            }

            if (request()->has('secondary_contact_number')) {
                $secondary_primary_contact_number = ['secondary_contact_number' => 'unique:resellers_secondary_numbers,secondary_number,'.request('username_id').",username_id"];
                $requested_data[] = [
                    "column" => "secondary_contact_number",
                    "column_id" => request('secondary_contact_number_id'),
                    "value" => request('secondary_contact_number')
                ];
            }

            if (request()->has('address')) {
                $requested_data[] = [
                    "column" => "address",
                    "column_id" => request('address_id'),
                    "value" => request('address')
                ];
            }

            $validation_data = array_merge(
                $validate_email_add,
                $validate_address,
                $validate_contact_person,
                $validate_primary_contact_number,
                $secondary_primary_contact_number
            );

            $required_validation = Validator::make(request()->all(),$validation_data);

            
            if ($required_validation->fails()) {    
                $errors = $required_validation->messages();
                $status = "error";
            }else {
                $files = request()->file("profile_upload");
                if (!empty($files)) {
                    Storage::deleteDirectory("/public/avatars/".request('username_id'));

                    $filename = request('username_id').".".$files->getClientOriginalExtension();
                    Storage::put("public/avatars/".request('username_id')."/".$filename,file_get_contents($files));
                }
                if (request('edited_content') > 0) {
                    $resellersProfileReq = new ResellersProfileRequests();
                    $resellersProfileReq->username_id = request('username_id');
                    $resellersProfileReq->requested_data = json_encode($requested_data);
                    $resellersProfileReq->save();
                }

                $files = request()->file("banner_upload");
                if (!empty($files)) {
                    Storage::deleteDirectory("/public/seller-banner/".request('username_id'));

                    $filename = request('username_id').".".$files->getClientOriginalExtension();
                    Storage::put("public/seller-banner/".request('username_id')."/".$filename,file_get_contents($files));
                }
                if (request('edited_content') > 0) {
                    $resellersProfileReq = new ResellersProfileRequests();
                    $resellersProfileReq->username_id = request('username_id');
                    $resellersProfileReq->requested_data = json_encode($requested_data);
                    $resellersProfileReq->save();
                }
                

                $errors = [];
                $status = "sucess";
            }

            return response()->json(["status" => $status,"errors" => $errors], 200);
        }
        
        
        
    }

    public function about_us() {
        $about_us = Auth::user()->about_us;
        $data = $about_us? $about_us->about_us: "";
        $data = str_replace('"', "'", $data);
        return view('reseller.reseller-aboutus',
            ['data' => $data]
        );
    }

    public function shipping_policy() {
        $shipping_policy = Auth::user()->shipping_policy;
        $data = $shipping_policy? $shipping_policy->shipping_policy: "";
        $data = str_replace('"', "'", $data);
        return view('reseller.reseller-shippingpolicy',
            ['data' => $data]
        );
    }
    public function return_policy() {
        $return_policy = Auth::user()->return_policy;
        $data = $return_policy? $return_policy->return_policy: "";
        $data = str_replace('"', "'", $data);
        return view('reseller.reseller-returnpolicy',
            ['data' => $data]
        );
    }

    public function payment_information() {
        $payment_information = Auth::user()->payment_information;
        $data = $payment_information? $payment_information->payment_information: "";
        $data = str_replace('"', "'", $data);
        return view('reseller.reseller-paymentinformation',
            ['data' => $data]
        );
    }

    public function update_aboutus() {
        $about_us = ResellersAboutUses::where("username_id",Auth::user()->id)->first();

        if ($about_us) {
            $about_us->about_us = request('about_us');
            $about_us->username_id = Auth::user()->id;
            $about_us_saved = $about_us->save();
            if (!$about_us_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }
        }else{
            $about_us_add = new ResellersAboutUses();
            $about_us_add->about_us = request('about_us');
            $about_us_add->username_id = Auth::user()->id;
            $about_us_add_saved = $about_us_add->save();
            if (!$about_us_add_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }

        }
        return response()->json(["status" => $status], 200);
    }

    public function update_shipping_policy() {
        // dd("SHIPPINT POLICY");
        $shipping_policy = ResellersShippingPolicies::where("username_id",Auth::user()->id)->first();

        if ($shipping_policy) {
            $shipping_policy->shipping_policy = request('shipping_policy');
            $shipping_policy->username_id = Auth::user()->id;
            $shipping_policy_saved = $shipping_policy->save();
            if (!$shipping_policy_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }
        }else{
            $shipping_policy_add = new ResellersShippingPolicies();
            $shipping_policy_add->shipping_policy = request('shipping_policy');
            $shipping_policy_add->username_id = Auth::user()->id;
            $shipping_policy_add_saved = $shipping_policy_add->save();
            if (!$shipping_policy_add_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }

        }
        return response()->json(["status" => $status], 200);
    }


    public function update_return_policy() {
        // dd("SHIPPINT POLICY");
        $return_policy = ResellersReturnPolicies::where("username_id",Auth::user()->id)->first();

        if ($return_policy) {
            $return_policy->return_policy = request('return_policy');
            $return_policy->username_id = Auth::user()->id;
            $return_policy_saved = $return_policy->save();
            if (!$return_policy_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }
        }else{
            $return_policy_add = new ResellersReturnPolicies();
            $return_policy_add->return_policy = request('return_policy');
            $return_policy_add->username_id = Auth::user()->id;
            $return_policy_add_saved = $return_policy_add->save();
            if (!$return_policy_add_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }

        }
        return response()->json(["status" => $status], 200);
    }

    public function update_payment_information() {
        // dd("SHIPPINT POLICY");
        $payment_information = ResellersPaymentInformations::where("username_id",Auth::user()->id)->first();

        if ($payment_information) {
            $payment_information->payment_information = request('payment_information');
            $payment_information->username_id = Auth::user()->id;
            $payment_information_saved = $payment_information->save();
            if (!$payment_information_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }
        }else{
            $payment_information_add = new ResellersPaymentInformations();
            $payment_information_add->payment_information = request('payment_information');
            $payment_information_add->username_id = Auth::user()->id;
            $payment_information_add_saved = $payment_information_add->save();
            if (!$payment_information_add_saved) {
                $status = "error";
            }else{
                $status = "sucess";
            }

        }
        return response()->json(["status" => $status], 200);
    }
}
