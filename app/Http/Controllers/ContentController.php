<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Items;
use App\ResellersProfiles;

use App\Mail\VerifyResellers;
use Illuminate\Support\Facades\Mail;

class ContentController extends Controller
{
    public function details($sku) {
        $sku = urldecode($sku);
        $img = Storage::allFiles("/public/items/".strtolower($sku));
        $img_file = ['https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRVX4RgUYvaDyHQaEiejmjMy0ZbuEPqGkOwsxq9oAmPl3MQJIRC'];
        $name = '';
        $desc = '';
        if ($img) {
            $item = Items::where('sku',$sku)->first();
            $name = $item->product_name;
            $desc = $item->product_shortdesc;
            $img_url = Storage::url($img[0]);
            $filename = str_replace("/storage/items/".strtolower($sku)."/", "", $img_url);
            $img_file = [url("/storage/items/".strtolower($sku)."/".$filename)];
        }
        

        $content = [
            'name' => $name,
            'description' => $desc,
            'images' => $img_file,
        ];
        
        return json_encode($content);
    }

    public function description($sku) {
        $sku = urldecode($sku);
        $item = Items::where('sku',$sku)->first();
        $desc = '';
        if (isset($item->product_desc)) {
            $desc = $item->product_desc;
        }

        return $desc;
    }

    public function manufacturer_profile($name) {
        
        /* $profile = ResellersProfiles::with(['about_us','shipping_policy','return_policy','payment_information'])
                    ->where('reseller_name',$name)
                    ->first();
        $data = [];
        if($profile){
            $data = $profile->toArray();
        } */

        /* return view('content.manufacturer-profile',
            ['data' => $data]
        ); */
        $name = urldecode($name);
        $data = ResellersProfiles::resellers_infos($name);
        $data->profile_img = getProfilePhoto($data->id);
        $data->banner_img = getBannerPhoto($data->id);
        return json_encode($data);
    }

    public function gallery($sku) {
        $sku = urldecode($sku);
        $sku = strtolower($sku);
        $img = Storage::allFiles("/public/items/".$sku);
        $data = [];
        for ($i=0; $i < count($img); $i++) { 
            $img_url = Storage::url($img[$i]);
            $filename = str_replace("/storage/items/".$sku."/", "", $img_url);
            $img_file = url("/storage/items/".$sku."/".$filename);
            /* $img_file = url($img_url); */
            array_push($data,$img_file);
        }
        return json_encode($data);
        /* return view('content.gallery',
            ['data' => $data]
        ); */
    }

    public function manufacturer_policies($sku) {
        $sku = urldecode($sku);
        $data = Items::select('username_id')
                    ->with(
                        [
                            'return_policy' => function($query){
                                $query->select('username_id', 'return_policy');
                            },
                            'shipping_policy' => function($query){
                                $query->select('username_id', 'shipping_policy');
                            },
                            'payment_informations' => function($query){
                                $query->select('username_id', 'payment_information');
                            },
                            'profile' => function($query){
                                $query->select('username_id', 'reseller_name');
                            },
                        ]
                    )
                    ->where('sku', $sku)->first();
        /* dd($data); */

        return view('content.policies',
            ['data' => $data]
        );
    }

    public function email_test() {
        $profiles = ResellersProfiles::find(1);
        
        $profiles->action_type = 101;
        mail::to('carl.romutap@iwebsolutionsphilippines.com')->send(new VerifyResellers($profiles));
        
        if (mail::failures()) {
            
        }else{
            return "EMAIL SENT";
        }
        
    }
}

