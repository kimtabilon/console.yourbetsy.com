<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mail\VerifyResellers;
use Illuminate\Support\Facades\Mail;
use \stdClass;

use App\Resellers;
use App\ResellersStatuses;
use App\ResellersProfiles;
use App\ResellersProfileRequests;
use App\ResellersEmailAddresses;
use App\ResellersAddresses;
use App\ResellersMobileNumbers;
use App\ResellersSecondaryNumbers;


class ResellerManagementController extends Controller
{
    public function pending_resellers() {
        return view('admin.verify-resellers',[
            'resellers_pending' => Resellers::pending_resellers([1]),
            'resellers_declined' => Resellers::pending_resellers([2]),
        ]);
    }

    public function reseller_change_status() {
        $reseller_status = ResellersStatuses::find(request('verify_reseller_id'));
        $reseller_status->status = request('status') == 30 ? 0 : request('status');
        $saved = $reseller_status->save();

        if(!$saved){
            $status = "unsuccessful";
        }else{
            $status = "successful";
            
            $reseller_profile = ResellersProfiles::where('username_id',$reseller_status->username_id)->first();
            $reseller_profile->action_type = request('status') == 30 ? 400 : request('status');
            mail::to($reseller_profile->emailaddress->email_address)->send(new VerifyResellers($reseller_profile));
        
            // return redirect()->back()
            // ->with('data', ['status' => $status,'reseller_name' => request('verify_reseller_name')]);
        }
        return response()->json(
            ['status' => $status]
        , 200);
            
        /* $reseller_profile = ResellersProfiles::where('username_id',$reseller_status->username_id)->first();
            $reseller_profile->action_type = request('status');
            mail::to($reseller_profile->emailaddress->email_address)->send(new VerifyResellers($reseller_profile));
        if (mail::failures()) {
            return response()->json(
                ['status' => "FAILED"]
            , 200);
        }else{
            return response()->json(
                ['status' => "Sent"]
            , 200);
        } */
        
        // return redirect()->back()
        //     ->with('data', ['status' => 'successful','reseller_name' => request('verify_reseller_name')]);
    }

    public function view_reseller_detalis() {
        return response()->json(
            Resellers::resellers_by_userID(request('id'))
        , 200);
    }

    public function active_resellers() {
        return view('admin.suspend_disable-resellers',[
            'resellers_active' => Resellers::pending_resellers([0]),
            'resellers_suspenddisabled' => Resellers::pending_resellers([3,4])
        ]);
    }

    public function reseller_profile_update_request() {
        return view('admin.update_profile_request-resellers',
            [
                "pending" => $this->getProfileRequestByStatus([1]),
                "approved_declined" => $this->getProfileRequestByStatus([0,2])
            ]
        );
    }

    public function getProfileRequestByStatus($status) {
        $request_list = ResellersProfileRequests::with(['resellerDetails','resellerProfile'])->whereIn("status",$status)->get();
        $data_array = []; 
        foreach ($request_list as $key => $value) {
            $row_arr = new stdClass;
            $row_arr->username = $value->resellerDetails->username;
            $row_arr->vendor_name = $value->resellerProfile->reseller_name;
            $row_arr->vendor_type = Reseller_type($value->resellerProfile->reseller_type);
            $row_arr->date_request = $value->created_at;
            $row_arr->status = getProfileEditRequestStatusName($value->status);
            $row_arr->id = $value->id;
            $data_array[] = $row_arr;
        }

        return $data_array;
    }

    public function requested_details() {
        $data = ResellersProfileRequests::with(['resellerProfile'])->where("resellers_profile_requests.id",request("id"))->first();
        $details_to_update = new stdClass;
        foreach (json_decode($data->requested_data) as $key => $value) {
            switch ($value->column) {
                case "email_address":
                    $details_to_update->email_address = $value->value;
                    break;
                case "address":
                    $details_to_update->address = $value->value;
                    break;
                case "contact_person":
                    $details_to_update->contact_person = $value->value;
                    break;
                case "primary_contact_number":
                    $details_to_update->primary_contact_number = $value->value;
                    break;
                case "secondary_contact_number":
                    $details_to_update->secondary_contact_number = $value->value;
                    break;
                default:
                    # code...
                    break;
            }
        }
        $data->status = getProfileEditRequestStatusName($data->status);
        $data->details_requested = $details_to_update;

        return response()->json(
            ['data' => $data]
        , 200);
    }

    public function reseller_change_profilerequest_status() {
        /* $profileupdate_status = ResellersProfileRequests::find(request('request_id'));
        $reseller_profile = ResellersProfiles::where('username_id',$profileupdate_status->username_id)->first();
        $reseller_profile->action_type = 100;
        mail::to($reseller_profile->emailaddress->email_address)->send(new VerifyResellers($reseller_profile));
        if (mail::failures()) {
            return response()->json(
                ['status' => "FAILED"]
            , 200);
        }else{
            return response()->json(
                ['status' => "Sent"]
            , 200);
        } */
        
        $error_counter = 0;

        $profileupdate_status = ResellersProfileRequests::find(request('request_id'));
        $profileupdate_status->status = request('status');
        $saved = $profileupdate_status->save();

        if(!$saved) {
            $error_counter++;
        }

        if (request('status') == 0) {
            foreach (json_decode($profileupdate_status->requested_data) as $key => $value) {
                switch ($value->column) {
                    case "email_address":
                        $email_add = ResellersEmailAddresses::find($value->column_id);
                        $email_add->email_address = $value->value;
                        $saved_email = $email_add->save();
                        if (!$saved_email) { $error_counter++; }
                        break;
                    case "address":
                        $address = ResellersAddresses::find($value->column_id);
                        $address->address = $value->value;
                        $saved_address = $address->save();
                        if (!$saved_address) { $error_counter++; }
                        break;
                    case "contact_person":
                        $profile = ResellersProfiles::find($value->column_id);
                        $profile->contact_person = $value->value;
                        $saved_profile = $profile->save();
                        if (!$saved_profile) { $error_counter++; }
                        break;
                    case "primary_contact_number":
                        $mobile = ResellersMobileNumbers::find($value->column_id);
                        $mobile->mobile_number = $value->value;
                        $saved_mobile = $mobile->save();
                        if (!$saved_mobile) { $error_counter++; }
                        break;
                    case "secondary_contact_number":
                        $secondary_no = ResellersSecondaryNumbers::find($value->column_id);
                        $secondary_no->secondary_number = $value->value;
                        $saved_secondary_no = $secondary_no->save();
                        if (!$saved_secondary_no) { $error_counter++; }
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
        
        
        if($error_counter == 0) {
            $status = "successful";
            $reseller_profile = ResellersProfiles::where('username_id',$profileupdate_status->username_id)->first();

            $email_action_type = "";
            if (request('status') == 0) {
                $email_action_type = 100;
            }else{
                $email_action_type = 200;
            }
            $reseller_profile->action_type = $email_action_type;
            mail::to($reseller_profile->emailaddress->email_address)->send(new VerifyResellers($reseller_profile));
        }else {
            $status = "unsuccessful";
        }

        return response()->json(
            ['status' => $status]
        , 200);
    }
}
