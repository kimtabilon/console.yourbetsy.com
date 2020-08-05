<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VerifyResellers;
use Illuminate\Support\Facades\Mail;
use Storage;
use App\Mail\Item;
use \stdClass;

use Auth;
use App\Resellers;
use App\ResellersProfiles;
use App\Items;
use App\ItemsHistories;
use App\ItemsCategories;
use App\User;

class ProductManangementController extends Controller
{
    public function pending_products() {
        /* $pending_items = Items::with(['profile','item_histories'])->where('status', 1)->get();
        return view('admin.verify-product',[
            'pending_products' => $pending_items
        ]); */
        $data = Items::with(['profile','item_histories'])->where('status', 1)->get();
        $pending_items = [];
        foreach ($data as $key => $value) {
            $row = new stdClass;
            

            $row->id = $value->id;
            $row->sku = $value->sku;
            $row->product_name = $value->product_name;
            $row->product_shortdesc = $value->product_shortdesc;
            $row->price = $value->price;
            $row->quantity = $value->quantity;
            $row->handling_time = $value->handling_time;
            $row->profile = $value->profile->reseller_name;
            
            $check_resubmit = ItemsHistories::where('item_id', $value->id)
                                            ->where("type_of_modifier","Vendor")
                                            ->where("action","Resubmit")
                                            ->get()->count();

            if ($check_resubmit > 0) {
                $row->status = "Pending - Resubmit";
            }else {
                $row->status = Status_type($value->status);
            }

            $pending_items[] = $row;
        }

        $data_declined = Items::with(['profile','item_histories'])->where('status', 2)->get();
        $pending_items_declined = [];
        foreach ($data_declined as $key => $value) {
            $row = new stdClass;
            

            $row->id = $value->id;
            $row->sku = $value->sku;
            $row->product_name = $value->product_name;
            $row->product_shortdesc = $value->product_shortdesc;
            $row->price = $value->price;
            $row->quantity = $value->quantity;
            $row->handling_time = $value->handling_time;
            $row->profile = $value->profile->reseller_name;
            
            $check_resubmit = ItemsHistories::where('item_id', $value->id)
                                            ->where("type_of_modifier","Vendor")
                                            ->where("action","Resubmit")
                                            ->get()->count();

            $row->status = Status_type($value->status);

            $pending_items_declined[] = $row;
        }

        return view('admin.verify-product',[
            'pending_products' => $pending_items,
            'declined_products' => $pending_items_declined
        ]);
    }

    public function active_products() {
        $active_items = Items::with(['profile','item_histories'])->where('status', 0)->get();
        $sus_dis_items = Items::with(['profile','item_histories'])->whereIn('status', [3,4])->get();
        return view('admin.suspend_disable-product',[
            'active_products' => $active_items,
            'sus_dis_products' => $sus_dis_items
        ]);
    }

    public function item_details() {
        
        $data = Items::with(['items_sub_categories'])->find(request('id'));
        $sku = strtolower($data->sku);
        $img = Storage::allFiles("/public/items/".$sku);
        $img_list = [];
        for ($i=0; $i < count($img); $i++) { 
            $img_url = Storage::url($img[$i]);
            /* array_push($img_list, asset($img_url)); */
            $filename = str_replace("/storage/items/".$sku."/", "", $img_url);
            $img_file = url("/storage/items/".$sku."/".$filename);
            array_push($img_list, $img_file);
        }

        $data->img = $img_list;
        $data_history = ItemsHistories::where("item_id",request('id'))->get();
        $history_arr = [];
        
        foreach ($data_history as $key => $value) {
            $row = new stdClass;

            if ($value->type_of_modifier == "Admin") {
                $modifier_name = User::find($value->modified_by)->name;
            }else{
                $modifier_name = ResellersProfiles::where("username_id",$value->modified_by)->first()->reseller_name;
            }
            $row->modifier = $modifier_name;
            $row->action = $value->action;
            $row->date = date("Y-m-d H:i:s",strtotime($value->created_at));
            $row->status = Status_type($value->status);

            if ($value->description) {
                $modified_val = json_decode($value->description);
                $new_v = "<b>New Details <br></b>";
                foreach(json_decode($modified_val->new) as $key => $value_n) {
                    $new_v .= $key.": ".$value_n."</br> ";
                }
                $row->new_val = $new_v;
                $old_v = "<b>Old Details <br></b>";
                foreach(json_decode($modified_val->old) as $key => $value_o) {
                    $old_v .= $key.": ".$value_o."</br> ";
                }
                $row->old_val = $old_v;
            }else{
                $row->new_val = "";
                $row->old_val = "";
            }

            $history_arr[] = $row;
        }
        $data->category = ItemsCategories::find($data->items_sub_categories->category_id)->category_name;
        $data->history =  $history_arr;
        return response()->json(["data" => $data], 200);
    }

    public function product_change_status() {
        $product_status = Items::find(request('verify_product_id'));
        $product_status->status = request('status');
        $saved = $product_status->save();

        if(!$saved){
            $status = "unsuccessful";
        }else{
            $status = "successful";

            $item_history = New ItemsHistories();
            $item_history->item_id = request('verify_product_id');
            $item_history->status = request('status');
            $item_history->date_modified = date("Y-m-d H:i:s");
            $item_history->modified_by = Auth::user()->id;
            $item_history->action = request('action');
            $item_history_saved = $item_history->save();
            
            $product = Items::with(['profile','email_add','items_sub_categories'])->find(request('verify_product_id'));
            $email_type = "";
            $email_stat = request('status');

            if ($email_stat == 0) {
                $this->addItemDetailsToStore($product);
            }


            if (request('action') == "Reactivate") {
                $email_stat = 6;
            }
            switch ($email_stat) {
                case 0:
                    $email_type = "itemVerification";
                    break;
                case 3:
                    $email_type = "itemSuspended";
                    break;
                case 4:
                    $email_type = "itemDisabled";
                    break;
                case 6:
                    $email_type = "itemReactivate";
                    break;
                
                default:
                    # code...
                    break;
            }
            $product->email_type = $email_type;
            mail::to($product->email_add->email_address)->send(new Item($product));
            if (mail::failures()) {
                return response()->json(
                    ['status' => $status]
                , 200);
            }else{
            }
        }
        return response()->json(
            ['status' => $status]
        , 200);
    }

    public function product_decline() {
        $item_status = (request('allow_resubmit') == "on"? 5 : request('status'));
        $product_status = Items::find(request('decline_product_id'));
        $product_status->status = $item_status;
        $saved = $product_status->save();

        if(!$saved){
            $status = "unsuccessful";
        }else{

            $item_history = New ItemsHistories();
            $item_history->item_id = request('decline_product_id');
            $item_history->status = $item_status;
            $item_history->date_modified = date("Y-m-d H:i:s");
            $item_history->modified_by = Auth::user()->id;
            if ($item_status == 5) {
                $item_history->action = "Declined - Resubmit";
            }else{
                $item_history->action = "Declined";
            }

            $item_history_saved = $item_history->save();

            if(!$item_history_saved){
                $status = "unsuccessful";
            }else{
                $status = "successful";
                $product = Items::with(['profile','email_add'])->find(request('decline_product_id'));
                $product->email_type = ($item_status == 5? "ItemResubmit" : "itemDeclined");
                mail::to($product->email_add->email_address)->send(new Item($product));
                if (mail::failures()) {
                    return response()->json(
                        ['status' => $status]
                    , 200);
                }else{
                }
            }
        }
        return response()->json(
            ['status' => $status]
        , 200);
    }

    function view_decline() {
        $data = ItemsHistories::where("item_id",request('id'))->get();
        return response()->json(["data" => $data], 200);
    }

    /* public function addItemDetailsToStore($items) {
        $betsy_api = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);

        $url_data = "";
        $price = 0;
        $qty = "";

        $counter_itemExist = 0;
        foreach ($json_data as $key => $value) {
            if ($items->sku == $value->sku) {
                $counter_itemExist++;
                break;
            }
        }
        
        if($counter_itemExist == 0) {
            $url_data = urlencode($items->sku).'~'.urlencode($items->price).'~'.'0'.'~'.urlencode($items->quantity).'~'.urlencode($items->product_name).'~'
            .urlencode($items->product_desc).'~'.urlencode($items->reseller_name).'~'.'0';
            $url = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/save~".$url_data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $exe_result = curl_exec($ch);
            $exe_err = curl_errno($ch);
            curl_close($ch);
        }
    } */

    public function addItemDetailsToStore($items) {

        $betsy_api = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);

        $counter_itemExist = 0;
        foreach ($json_data as $key => $value) {
            if ($items->sku == $value->sku) {
                $counter_itemExist++;
                break;
            }
        }
        if($counter_itemExist == 0) {
            
            $token_details = storeToken();
            dd($token_details);
            $ch = curl_init($token_details['domain']."/rest/V1/products");

            $category = ItemsCategories::find($items->items_sub_categories->category_id);
            // $country_of_manufacture = ($items->made_in? $items->made_in : "");

            $json = new stdClass;
            if ($items->special_price > 0) {
                $json->product = [
                    "sku" => $items->sku,
                    "name" => $items->product_name,
                    "attribute_set_id" => 4,
                    "price" => $items->price,
                    "status" => 1,
                    "visibility" => 4,
                    "type_id" => "simple",
                    "weight" => "0",
                    "extension_attributes" => [
                        "category_links" =>[
                            [
                                "position" => 0,
                                "category_id" => $category->store_cat_id
                            ],
                            [
                                "position" => 1,
                                "category_id" => $items->items_sub_categories->store_subcat_id
                            ],
                        ],
                        "stock_item" =>[
                            "qty" => $items->quantity,
                            "is_in_stock" => true
                        ]
                    ],
                    "custom_attributes" => [
                        [
                            "attribute_code" => "description",
                            "value" => $items->product_desc
                        ],
                        [
                            "attribute_code" => "tax_class_id",
                            "value" => "0"
                        ],
                        [
                            "attribute_code" => "manufacturer",
                            "value" => $items->profile->reseller_name
                        ],
                        [
                            "attribute_code" => "special_from_date",
                            "value" => $items->date_start
                        ],
                        [
                            "attribute_code" => "special_to_date",
                            "value" => $items->date_end
                        ],
                        [
                            "attribute_code" => "special_price",
                            "value" => $special_price
                        ],
                        [
                            "attribute_code" => "short_description",
                            "value" => $items->product_shortdesc
                        ],
                        /* [
                            "attribute_code" => "country_of_manufacture",
                            "value" => $country_of_manufacture
                        ], */
                    ]
                ];
            }else{
                $json->product = [
                    "sku" => $items->sku,
                    "name" => $items->product_name,
                    "attribute_set_id" => 4,
                    "price" => $items->price,
                    "status" => 1,
                    "visibility" => 4,
                    "type_id" => "simple",
                    "weight" => "0",
                    "extension_attributes" => [
                        "category_links" =>[
                            [
                                "position" => 0,
                                "category_id" => $category->store_cat_id
                            ],
                            [
                                "position" => 1,
                                "category_id" => $items->items_sub_categories->store_subcat_id
                            ],
                        ],
                        "stock_item" =>[
                            "qty" => $items->quantity,
                            "is_in_stock" => true
                        ]
                    ],
                    "custom_attributes" => [
                        [
                            "attribute_code" => "description",
                            "value" => $items->product_desc
                        ],
                        [
                            "attribute_code" => "tax_class_id",
                            "value" => "0"
                        ],
                        [
                            "attribute_code" => "manufacturer",
                            "value" => $items->profile->reseller_name
                        ],
                        [
                            "attribute_code" => "short_description",
                            "value" => $items->product_shortdesc
                        ],
                        /* [
                            "attribute_code" => "country_of_manufacture",
                            "value" => $country_of_manufacture
                        ], */
                    ]
                ];
            }
            
            
            $json  = json_encode($json);
            
            $curlOptions = array(
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
                );
                
                curl_setopt_array( $ch, $curlOptions );
                
                $response = curl_exec( $ch );
        }

        
    }

    public function uploadThumbnailToStore($sku) {
        ini_set('max_execution_time', 5000);
        $img = Storage::allFiles("/public/items/".$sku);
        $img = Storage::url($img[0]);
         /* $filename = str_replace("/storage/items/".$sku."/", "", $img[0]);
            $img_file = url("/storage/app/public/items/".$sku."/".$filename);
        $img = file_get_contents($img_file);
        $img = base64_encode($img);  */
        /* $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL,url($img[0]));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        curl_setopt($ch, CURLOPT_POSTFIELDS,[]);
        $data = curl_exec($ch);
        curl_close($ch);
        $img = base64_encode($data); */

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, url($img[0]));

        $data = curl_exec($ch);
        curl_close($ch);

        $imageData = base64_encode($data);
dd($imageData);
        /* $token_details = storeToken();

        $ch = curl_init($token_details['domain']."/rest/V1/products");

        $json = [
            {
              "id": 0,
              "media_type": "test",
              "label": "test",
              "position": 0,
              "disabled": true,
              "types": [
                "thumbnail"
              ],
              "file": "test.png",
              "content": {
                "base64_encoded_data": "Here i have passed base64_encoded data of image",
                "type": "file/png",
                "name": "test.png"
              },
              }
            }
        ]; */

        
    }

    public function bse64_img($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
