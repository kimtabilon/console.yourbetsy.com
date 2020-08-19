<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;
use \stdClass;

use App\Resellers;
use App\Items;
use App\ItemsHistories;
use App\ItemsCategories;
use App\ItemsSubCategories;
use App\ResellersProfiles;
use Auth;

class ResellerItemController extends Controller
{
    public function index()
    {
        /* $data = Items::whereIn('status', [1,5,2])
                ->where('username_id',Auth::user()->id)
                ->get(); */
        $reseller = Auth::user()->profile;
        $seller_allowed = [];
        if ($reseller->reseller_position == 1) {
            $seller_allowed = [$reseller->id,$reseller->parent];
        }else{
            $sellers_second_users = ResellersProfiles::select('username_id')
                                    ->where('parent',$reseller->id)
                                    ->pluck('username_id')->toArray();
            $seller_allowed = $sellers_second_users;
            array_push($seller_allowed, $reseller->id);
        }
        /* $data = Items::where('username_id',Auth::user()->id)
                ->get(); */
        $data = Items::whereIn('username_id',$seller_allowed)
                ->get();
        // dd($data);
        $pending_items = [];
        $deactivated_items = [];
        foreach ($data as $key => $value) {
            $row = new stdClass;
            
            $row->id = $value->id;
            $row->sku = $value->sku;
            $row->product_name = $value->product_name;
            $row->product_shortdesc = $value->product_shortdesc;
            $row->price = $value->price;
            $row->quantity = $value->quantity;
            $row->handling_time = $value->handling_time;
            $row->special_price = $value->special_price;
            

           /*  if ($value->status == 2) {
                // $row->status = Status_type($value->status);
                // $deactivated_items[] = $row;
            }else{

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
            } */
            
            if ($value->status == 1) {
                $check_resubmit = ItemsHistories::where('item_id', $value->id)
                                            ->where("type_of_modifier","Vendor")
                                            ->where("action","Resubmit")
                                            ->get()->count();
                if ($check_resubmit > 0) {
                    $row->status = "Pending - Resubmit";
                }else{
                    $row->status = Status_type($value->status);
                }
            }else{
                $row->status = Status_type($value->status);
            }

            $pending_items[] = $row;

            
        }

        /* $active_item = Items::where('status', 0)
                        ->where('username_id',Auth::user()->id) 
                        ->get();
        $inctive_item = Items::where('status', [3,4])
                        ->where('username_id',Auth::user()->id) 
                        ->get(); */

        $category_list = ItemsCategories::where("status",0)->get();
        return view('reseller.reseller-items',[
            'pending_item' => $pending_items,
            /* 'declined_item' => $deactivated_items,
            'active_item' => $active_item,
            'inactive_item' => $inctive_item, */
            'category' => $category_list
        ]);
    }

    public function add_item() {
        $validate_data = [
            'sku' => ['required','unique:items,sku'],
            'product_name' => ['required'],
            'description' => ['required'],
            'short_description' => ['required'],
            'price' => ['required','numeric','min:0','not_in:0'],
            'quantity' => ['required','numeric','min:0','not_in:0'],
            'handling_time' => ['required'],
            'product_images' => ['required'],
            'sub_category' => ['required'],
            'shipping_fee' => ['required','numeric','min:0','not_in:0'],
            'special_price' => ['not_in:0'],
        ];

        if (request('special_price') > 0) {
            $validate_data = [
                'sku' => ['required','unique:items,sku'],
                'product_name' => ['required'],
                'description' => ['required'],
                'short_description' => ['required'],
                'price' => ['required','numeric','min:0','not_in:0'],
                'quantity' => ['required','numeric','min:0','not_in:0'],
                'handling_time' => ['required'],
                'product_images' => ['required'],
                'sub_category' => ['required'],
                'shipping_fee' => ['required','numeric','min:0','not_in:0'],
                'date_start' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }
        
        if (request('date_start') != "" || request('date_end') != "") {
            $validate_data = [
                'sku' => ['required','unique:items,sku'],
                'product_name' => ['required'],
                'description' => ['required'],
                'short_description' => ['required'],
                'price' => ['required','numeric','min:0','not_in:0'],
                'quantity' => ['required','numeric','min:0','not_in:0'],
                'handling_time' => ['required'],
                'product_images' => ['required'],
                'sub_category' => ['required'],
                'shipping_fee' => ['required','numeric','min:0','not_in:0'],
                'special_price' => ['required','numeric','min:0','not_in:0'],
                'date_start' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }
        $required_validation = Validator::make(request()->all(),$validate_data);
        

        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {
            $items = New Items();
            $items->sku = strtoupper(request('sku'));
            $items->sub_category_id = request('sub_category');
            $items->product_name = request('product_name');
            $items->product_desc = request('description');
            $items->product_shortdesc = request('short_description');
            $items->price = request('price');
            $items->quantity = request('quantity');
            $items->handling_time = request('handling_time');
            $items->special_price = request('special_price');
            $items->shipping_fee = request('shipping_fee');
            $items->date_start = request('date_start')?date("Y-m-d H:i:s",strtotime(request('date_start'))) : NULL;
            $items->date_end = request('date_start')?date("Y-m-d H:i:s",strtotime(request('date_end'))) : NULL;
            $items->made_in = request('made_in');
            $items->username_id =  Auth::user()->id;
            

            $check_itemDIR_exist = Storage::disk('local')->exists("public/items");
            if (!$check_itemDIR_exist) {
                Storage::makeDirectory("/public/items");
            }

            Storage::makeDirectory("/public/items/".strtolower(request('sku')));

            $count_image = 1;
            $files = request()->file("product_images");
            if (!empty($files)) {
                foreach ($files as $value) {
                    // Storage::put($value->getClientOriginalName(),file_get_contents($value));
                    $filename = strtolower(request('sku'))."_".$count_image.".".$value->getClientOriginalExtension();
                    Storage::put("public/items/".strtolower(request('sku'))."/".$filename,file_get_contents($value));
                    $count_image++;
                }
            }

            $items_saved = $items->save();

            $item_history = New ItemsHistories();
            $item_history->item_id = $items->id;
            $item_history->status = 1;
            $item_history->date_modified = date("Y-m-d H:i:s");
            $item_history->modified_by = Auth::user()->id;
            $item_history->action = "Added";
            $item_history->type_of_modifier = "Vendor";
            $item_history_saved = $item_history->save();

            $status = "sucess";
            $errors = [];
        }

        /* $check_itmDIR_exist = Storage::disk('local')->exists("public/items");
        if (!$check_dir_exists) {
            Storage::makeDirectory("/public/items");
        } */
        
        /* BACKUP */
        /* $dir = "public/items/";
        $check_dir_exists = Storage::disk('local')->exists($dir);
        if (!$check_dir_exists) {
            dd("not Exist");
        }else{
            dd("Exist");
        } */

        /* $count_image = 1;
        $files = request()->file("product_images");
        if (!empty($files)) {
            foreach ($files as $value) {
                // Storage::put($value->getClientOriginalName(),file_get_contents($value));
                $filename = "test_".$count_image.".".$value->getClientOriginalExtension();
                Storage::put("public/items/1AABS/".$filename,file_get_contents($value));
                $count_image++;
            }
        } */
        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function item_details() {
        $data = Items::with(['items_sub_categories'])->find(request('id'));
        $sku = strtolower($data->sku);
        $img = Storage::allFiles("/public/items/".strtolower($sku));
        $img_list = [];
        for ($i=0; $i < count($img); $i++) { 
            $img_url = Storage::url($img[$i]);
            $row_arr = [];
            
            /* $row_arr["url"] = asset($img_url); */
            $filename = str_replace("/storage/items/".strtolower($sku)."/", "", $img_url);
/*             $row_arr["url"] = url("/storage/app/public/items/".$sku."/".$filename);
            $row_arr["filename"] = str_replace("/storage/items/".strtolower($sku)."/", "", $img_url); */
            $row_arr["url"] = url("/storage/items/".$sku."/".$filename);
            $row_arr["filename"] = url("/storage/items/".$sku."/".$filename);
            
            // array_push($img_list, asset($img_url));
            $img_list[] = $row_arr;
        }
        $data->category = ItemsCategories::find($data->items_sub_categories->category_id)->category_name;
        $data->img = $img_list;
        return response()->json(["data" => $data], 200);
    }

    public function update_item() {

        $validate_data = [
            /* 'sku' => ['required','unique:items,sku'], */
            'product_name' => ['required'],
            'description' => ['required'],
            'short_description' => ['required'],
            'price' => ['required','numeric','min:0','not_in:0'],
            'quantity' => ['required','numeric','min:0','not_in:0'],
            'handling_time' => ['required'],
            'product_images' => ['required'],
            'sub_category' => ['required'],
            'shipping_fee' => ['required','numeric','min:0','not_in:0'],
            'special_price' => ['not_in:0'],
        ];

        if (request('special_price') > 0) {
            $validate_data = [
                /* 'sku' => ['required','unique:items,sku'], */
                'product_name' => ['required'],
                'description' => ['required'],
                'short_description' => ['required'],
                'price' => ['required','numeric','min:0','not_in:0'],
                'quantity' => ['required','numeric','min:0','not_in:0'],
                'handling_time' => ['required'],
                'product_images' => ['required'],
                'sub_category' => ['required'],
                'shipping_fee' => ['required','numeric','min:0','not_in:0'],
                'date_start' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }
        
        if (request('date_start') != "" || request('date_end') != "") {
            $validate_data = [
                /* 'sku' => ['required','unique:items,sku'], */
                'product_name' => ['required'],
                'description' => ['required'],
                'short_description' => ['required'],
                'price' => ['required','numeric','min:0','not_in:0'],
                'quantity' => ['required','numeric','min:0','not_in:0'],
                'handling_time' => ['required'],
                'product_images' => ['required'],
                'sub_category' => ['required'],
                'shipping_fee' => ['required','numeric','min:0','not_in:0'],
                'special_price' => ['required','numeric','min:0','not_in:0'],
                'date_start' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }

        if (request('img_count') == 0) {
            $validate_data = array_merge($validate_data,['product_images' => ['required']]);
        }
        $required_validation = Validator::make(request()->all(),$validate_data);
        
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {
            
            $items = Items::find(request('item_id'));
            $items->product_name = request('product_name');
            $items->product_desc = request('description');
            $items->product_shortdesc = request('short_description');
            $items->price = request('price');
            $items->quantity = request('quantity');
            $items->handling_time = request('handling_time');
            $items->special_price = request('special_price');
            $items->shipping_fee = request('shipping_fee');
            $items->date_start = request('date_start')?date("Y-m-d H:i:s",strtotime(request('date_start'))) : NULL;
            $items->date_end = request('date_end')?date("Y-m-d H:i:s",strtotime(request('date_end'))) : NULL;
            $items->made_in = request('made_in');
            $items->status = 1;
            
            $old_items = Items::find(request('item_id'));
            $desc_item_his_new = [];
            $desc_item_his_old = [];
            if ($old_items->product_name != request('product_name')) {
                $desc_item_his_new['product_name'] = request('product_name');
                $desc_item_his_old['product_name'] = $old_items->product_name;
            }
            if ($old_items->description != request('description')) {
                $desc_item_his_new['description'] = request('description');
                $desc_item_his_old['description'] = $old_items->description;
            }
            if ($old_items->product_shortdesc != request('short_description')) {
                $desc_item_his_new['product_shortdesc'] = request('short_description');
                $desc_item_his_old['product_shortdesc'] = $old_items->product_shortdesc;
            }
            if ($old_items->price != request('price')) {
                $desc_item_his_new['price'] = request('price');
                $desc_item_his_old['price'] = $old_items->price;
            }
            if ($old_items->shipping_fee != request('shipping_fee')) {
                $desc_item_his_new['shipping_fee'] = request('shipping_fee');
                $desc_item_his_old['shipping_fee'] = $old_items->shipping_fee;
            }
            if ($old_items->quantity != request('quantity')) {
                $desc_item_his_new['quantity'] = request('quantity');
                $desc_item_his_old['quantity'] = $old_items->quantity;
            }
            if ($old_items->handling_time != request('handling_time')) {
                $desc_item_his_new['handling_time'] = request('handling_time');
                $desc_item_his_old['handling_time'] = $old_items->handling_time;
            }
            if ($old_items->special_price != request('special_price')) {
                $desc_item_his_new['special_price'] = request('special_price');
                $desc_item_his_old['special_price'] = $old_items->special_price;
            }
            if ($old_items->special_price != request('special_price')) {
                $desc_item_his_new['special_price'] = request('special_price');
                $desc_item_his_old['special_price'] = $old_items->special_price;
            }
            if ( date("Y-m-d H:i:s",strtotime($old_items->date_start)) != $items->date_start) {
                $desc_item_his_new['date_start'] = $items->date_start;
                $desc_item_his_old['date_start'] = $old_items->date_start;
            }
            if ( date("Y-m-d H:i:s",strtotime($old_items->date_end)) != $items->date_end) {
                $desc_item_his_new['date_end'] = $items->date_end;
                $desc_item_his_old['date_end'] = $old_items->date_end;
            }
            if ($old_items->made_in != request('made_in')) {
                $desc_item_his_new['made_in'] = request('made_in');
                $desc_item_his_old['made_in'] = $old_items->made_in;
            }

            $data_desc_his = [
                "new" => json_encode($desc_item_his_new),
                "old" => json_encode($desc_item_his_old)
            ];
            $item_his_data = [
                'item_id' => $items->id,
                'status' => 1,
                'action' => "Update",
                'desc' => json_encode($data_desc_his),
                'type_of_modifier' => "Vendor"

            ];

            $this->item_history($item_his_data);


            $items_saved = $items->save();

            /* IMG HANDLING */
            
            
            /* Move FIles */
            $sku = strtolower($items->sku);
            $img = Storage::allFiles("/public/items/".$sku);
           
            if (count(json_decode(request('remove_img_num'))) != 0) {

                foreach (json_decode(request('remove_img_num')) as $value) {
                    Storage::delete("/public/items/".$sku."/".$value);
                }
                $img = Storage::allFiles("/public/items/".$sku);
                $count = 1;
                $temp_dir = "/public/items/".$sku."/temp";
                Storage::makeDirectory($temp_dir);
                foreach ($img as $key => $value) {
                    $infoPath = pathinfo(public_path($value));
                    Storage::move($value, "/public/items/".$sku."/temp/".$sku."_".$count.".".$infoPath['extension']);
                    $count++;
                }
                /* Return Files */
                $count = 1;
                $temp_img = Storage::allFiles("/public/items/".$sku."/temp");
                foreach ($temp_img as $key => $value) {
                    $infoPath = pathinfo(public_path($value));
                    Storage::move($value, "/public/items/".$sku."/".$sku."_".$count.".".$infoPath['extension']);
                    $count++;
                }
                /* Delete tempfolder */
                Storage::deleteDirectory("/public/items/".$sku."/temp");
            }else{
                $count = count($img)+1;
            }
            
            /* Upload new images */
            
            $files = request()->file("product_images");
            if (!empty($files)) {
                foreach ($files as $value) {
                    // Storage::put($value->getClientOriginalName(),file_get_contents($value));
                    $filename = $sku."_".$count.".".$value->getClientOriginalExtension();
                    Storage::put("public/items/".$sku."/".$filename,file_get_contents($value));
                    $count++;
                }
            }

            /* IMG HANDLING */


            $status = "sucess";
            $errors = [];
        }
        return response()->json(["status" => $status,"errors" => $errors], 200);
        /* Storage::move("/public/items/XZZ0000/XZZ0000_1.jpg", "/public/items/XZZ0000/NANIII.jpg"); */
    }

    public function item_history($data) {
        $item_history = New ItemsHistories();
        $item_history->item_id = $data["item_id"];
        $item_history->status = $data["status"];
        $item_history->date_modified = date("Y-m-d H:i:s");
        $item_history->modified_by = Auth::user()->id;
        $item_history->type_of_modifier = $data['type_of_modifier'];
        $item_history->action = $data["action"];
        $item_history->description = $data["desc"];
        $item_history_saved = $item_history->save();
    }

    public function sub_category() {
        $data = itemsSubCategories::where('category_id',request('id'))->where('status',0)->get();
        return response()->json($data, 200);

    }

    public function update_pq_item() {
        

        $validate_data = [
            'price_update' => ['required','numeric','min:0','not_in:0'],
            'quantity_update' => ['required','numeric','min:0','not_in:0'],
            'shipping_fee_update' => ['required','numeric','min:0','not_in:0'],
            'special_price_update' => ['not_in:0'],
        ];
        if (request('special_price_update') > 0) {
            $validate_data = [
                'price_update' => ['required','numeric','min:0','not_in:0'],
                'quantity_update' => ['required','numeric','min:0','not_in:0'],
                'shipping_fee_update' => ['required','numeric','min:0','not_in:0'],
                'date_start_update' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end_update' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }
        
        
        if (request('date_start_update') != "" || request('date_end_update') != "") {
            $validate_data = [
                'price_update' => ['required','numeric','min:0','not_in:0'],
                'quantity_update' => ['required','numeric','min:0','not_in:0'],
                'special_price_update' => ['required','numeric','min:0','not_in:0'],
                'shipping_fee_update' => ['required','numeric','min:0','not_in:0'],
                'date_start_update' => ['required', 'date' ,'date_format:m/d/Y'],
                'date_end_update' => ['required', 'date' ,'date_format:m/d/Y']
            ];
        }

        $required_validation = Validator::make(request()->all(),$validate_data);
        
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {
            
            $items = Items::find(request('item_id_update'));
            $items->price = request('price_update');
            $items->quantity = request('quantity_update');
            $items->special_price = request('special_price_update');

            if (request('date_start_update')) {
                $items->date_start = date("Y-m-d H:i:s",strtotime(request('date_start_update')));
            }

            if (request('date_end_update')) {
                $items->date_end = date("Y-m-d H:i:s",strtotime(request('date_end_update')));
            }
            
            $old_items = Items::find(request('item_id_update'));
            $desc_item_his_new = [];
            $desc_item_his_old = [];
            
            if ($old_items->price != request('price_update')) {
                $desc_item_his_new['price'] = request('price_update');
                $desc_item_his_old['price'] = $old_items->price;
            }

            if ($old_items->quantity != request('quantity_update')) {
                $desc_item_his_new['quantity'] = request('quantity_update');
                $desc_item_his_old['quantity'] = $old_items->quantity;
            }
 
            if ($old_items->special_price != request('special_price_update')) {
                $desc_item_his_new['special_price'] = request('special_price_update');
                $desc_item_his_old['special_price'] = $old_items->special_price;
            }

            if ( date("Y-m-d H:i:s",strtotime($old_items->date_start)) != $items->date_start) {
                $desc_item_his_new['date_start'] = $items->date_start;
                $desc_item_his_old['date_start'] = $old_items->date_start;
            }
            if ( date("Y-m-d H:i:s",strtotime($old_items->date_end)) != $items->date_end) {
                $desc_item_his_new['date_end'] = $items->date_end;
                $desc_item_his_old['date_end'] = $old_items->date_end;
            }

            $data_desc_his = [
                "new" => json_encode($desc_item_his_new),
                "old" => json_encode($desc_item_his_old)
            ];
            $item_his_data = [
                'item_id' => $items->id,
                'status' => 1,
                'action' => "Update",
                'desc' => json_encode($data_desc_his),
                'type_of_modifier' => "Vendor"

            ];

            $this->item_history($item_his_data);


            $items_saved = $items->save();

            /* UPDATE TO STORE */
            $this->updateItemDetailsToStore($items);

            $status = "sucess";
            $errors = [];
        }
        
        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function updateItemDetailsToStore($items) {

        $betsy_api = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);

        $url_data = "";
        $price = 0;
        $qty = "";
        $shipping_fee = "";
        $counter_difference = 0;
        foreach ($json_data as $key => $value) {
            if ($value->sku == $items->sku) {
                if ($items->price !== $value->price) {
                    $price = $items->price;
                    $counter_difference++;
                }else{
                    $price = $value->price;
                }

                if ($items->quantity !== $value->qty) {
                    $qty = $items->quantity;
                    $counter_difference++;
                }else{
                    $qty = $value->qty;
                }

                $shipping_fee = $items->shipping_fee;
                $counter_difference++;
            break;
            }
            
        }
        
        if($counter_difference > 0) {
            /* $profile = ResellersProfiles::where('username_id',$items->username_id)->first();
            $url_data = urlencode($items->sku).'~'.urlencode($price).'~'.'0'.'~'.urlencode($qty).'~'.urlencode($items->product_name).'~'
            .urlencode($items->product_desc).'~'.urlencode($profile->reseller_name).'~'.'0';
            $url = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/save~".$url_data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $exe_result = curl_exec($ch);
            $exe_err = curl_errno($ch);
            curl_close($ch); */

            /* if($exe_result === false) {
                return $exe_err;
            } */

            $token_details = storeToken();

            $ch = curl_init($token_details['domain']."/rest/all/V1/products/".$items->sku);
            $json = new stdClass;

            if ($price != 0 && $qty != "") {
                $json->product = [
                    "price" => $price,
                    "extension_attributes" => [
                        "stock_item" =>[
                            "qty" => $qty,
                            "is_in_stock" => true
                        ]
                    ],
                    "custom_attributes" => [
                        [
                            "attribute_code" => "shipping_cost",
                            "value" => $items->shipping_fee
                        ],
                    ]
                ];
            }else {
                $json->product = [
                    "custom_attributes" => [
                        [
                            "attribute_code" => "shipping_cost",
                            "value" => $items->shipping_fee
                        ],
                    ]
                ];
            }
            

            $json  = json_encode($json);
            
            $curlOptions = array(
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
                );
                
                curl_setopt_array( $ch, $curlOptions );
                
                $response = curl_exec( $ch );
        }
    }
}
