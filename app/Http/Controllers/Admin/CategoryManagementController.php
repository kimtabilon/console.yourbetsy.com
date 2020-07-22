<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\ItemsCategories;
use App\ItemsSubCategories;

class CategoryManagementController extends Controller
{
    public function index() {
        $active = ItemsCategories::with(['items_sub_categories'])->where("status",0)->get();
        $inactive = ItemsCategories::where("status",1)->get();
        return view('admin.admin-category',[
            'active' => $active,
            'inactive' => $inactive
        ]);
    }

    public function add() {
        $required_validation = Validator::make(request()->all(),['category_name' => ['required','unique:items_categories,category_name']]);
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {
            /* Add Category to store */
            $store_data = [
                'action' => "add",
                'parent_id' => 2,
                'name' => ucwords(request('category_name')),
                'level' => 2
            ];
            $res_addcatstore = $this->addCategoryInStore($store_data);
            $res_addcatstore = json_decode($res_addcatstore);

            $category = new ItemsCategories;
            $category->category_name = ucwords(request('category_name'));
            $category->store_cat_id = $res_addcatstore->id;
            $category_saved = $category->save();
            
            $status = "success";
            $errors = [];
        }

        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function update() {
        $category = ItemsCategories::find(request("category_id"));
        if (request("category_name") != $category->category_name) {
            $data_validation = ['required','unique:items_categories,category_name'];
        }else{
            $data_validation = ['required'];
        }
        $required_validation = Validator::make(request()->all(),['category_name' => $data_validation]);
        
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {

            /* Update Category to store */
            $store_data = [
                'action' => "update",
                'parent_id' => 2,
                'name' => ucwords(request('category_name')),
                'level' => 2,
                'cat_id' => $category->store_cat_id
            ];
            $res_addcatstore = $this->addCategoryInStore($store_data);

            $category->category_name = request('category_name');
            $category_saved = $category->save();
            
            $status = "success";
            $errors = [];
        }

        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function change_status() {
        $category = ItemsCategories::find(request("deact_category_id"));
        $category->status = request('status');
        $category_saved = $category->save();

        /* Update Category to store */
        $active_stat = request('status') == 0 ? 1 : 0;
        
        $store_data = [
            'action' => "active",
            'active_stat' => $active_stat,
            'cat_id' => $category->store_cat_id
        ];
        $res_addcatstore = $this->addCategoryInStore($store_data);

        return response()->json(["status" => "success"], 200);
    }

    public function delete() {
        $category = ItemsCategories::find(request("del_category_id"));
        $store_cat_id = $category->store_cat_id;
        $category_saved = $category->delete();

        $sub_category = ItemsSubCategories::where('category_id',request("del_category_id"));
        $sub_category->delete();
        /* Update Category to store */
        $store_data = [
            'action' => "delete",
            'cat_id' => $store_cat_id
        ];
        $res_addcatstore = $this->addCategoryInStore($store_data);

        return response()->json(["status" => "success"], 200);
    }

    public function add_sub() {
        $required_validation = Validator::make(request()->all(),['sub_category_name' => ['required','unique:items_sub_categories,sub_category_name']]);
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {
            $item_category_details = ItemsCategories::find(request('category_id_SB'));

            /* Add Category to store */
            $store_data = [
                'action' => "add",
                'parent_id' => $item_category_details->store_cat_id,
                'name' => ucwords(request('sub_category_name')),
                'level' => 3
            ];
            $res_addcatstore = $this->addCategoryInStore($store_data);
            $res_addcatstore = json_decode($res_addcatstore);

            $category = new ItemsSubCategories;
            $category->sub_category_name = ucwords(request('sub_category_name'));
            $category->category_id = request('category_id_SB');
            $category->store_subcat_id = $res_addcatstore->id;
            $category_saved = $category->save();
            
            $status = "success";
            $errors = [];
        }

        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function update_sub() {
        $sub_category = ItemsSubCategories::with('category')->find(request("sub_category_id"));

        if (request("sub_category_id") != $sub_category->id) {
            $data_validation = ['required','unique:items_sub_categories,sub_category_name'];
        }else{
            $data_validation = ['required'];
        }
        $required_validation = Validator::make(request()->all(),['sub_category_name' => $data_validation]);
        
        if ($required_validation->fails()) {   
            $errors = $required_validation->messages();
            $status = "error";
        }else {

            /* Update Category to store */
            $store_data = [
                'action' => "update",
                'parent_id' => $sub_category->category->store_cat_id,
                'name' => ucwords(request('sub_category_name')),
                'level' => 3,
                'cat_id' => $sub_category->store_subcat_id
            ];
            $res_addcatstore = $this->addCategoryInStore($store_data);

            $sub_category->sub_category_name = request('sub_category_name');
            $sub_category_saved = $sub_category->save();
            
            $status = "success";
            $errors = [];
        }

        return response()->json(["status" => $status,"errors" => $errors], 200);
    }

    public function subcategory_list() {
        $data_active = ItemsSubCategories::with(['category'])->where('category_id',request('id'))->where('status',0)->get();
        $data_inactive = ItemsSubCategories::with(['category'])->where('category_id',request('id'))->where('status',1)->get();
        return response()->json([
            'active_subcat' => $data_active,
            'inactive_subcat' => $data_inactive,
        ], 200);
    }

    public function change_status_sub() {
        $category = ItemsSubCategories::find(request("deact_sub_category_id"));
        $category->status = request('status_subcat');
        $category_saved = $category->save();

        /* Update Category to store */
        $active_stat = request('status_subcat') == 0 ? 1 : 0;
        
        $store_data = [
            'action' => "active",
            'active_stat' => $active_stat,
            'cat_id' => $category->store_subcat_id
        ];
        $res_addcatstore = $this->addCategoryInStore($store_data);

        return response()->json(["status" => "success"], 200);
    }

    public function delete_sub() {
        $sub_category = ItemsSubCategories::find(request("del_sub_category_id"));
        $store_subcat_id = $sub_category->store_subcat_id;
        $sub_category->delete();
        /* Update Category to store */
        $store_data = [
            'action' => "delete",
            'cat_id' => $store_subcat_id
        ];
        $res_addcatstore = $this->addCategoryInStore($store_data);

        return response()->json(["status" => "success"], 200);
    }
    
    public function addCategoryInStore($data) {

        $token_details = storeToken();

        
        switch ($data['action']) {
            case 'add':
                $ch = curl_init($token_details['domain']."rest/V1/categories");

                $json = '{
                    "category": {
                        "name": "'.$data['name'].'",
                        "level": '.$data['level'].',
                        "parent_id": '.$data['parent_id'].',
                        "isActive": true
                    },
                    "saveOptions": true
                }';

                $response_type = "POST";
                break;
            
            case 'update':
                $ch = curl_init($token_details['domain']."rest/all/V1/categories/".$data['cat_id']."/");

                $json = '{
                    "category": {
                        "name": "'.$data['name'].'"
                    }
                }';
                $response_type = "PUT";
                break;
            case 'active':
                $ch = curl_init($token_details['domain']."rest/all/V1/categories/".$data['cat_id']."/");
                
                $json = '{
                    "category": {
                        "is_active": '.$data['active_stat'].'
                    }
                }';
                $response_type = "PUT";
                break;
            case 'delete':
                $ch = curl_init($token_details['domain']."rest/V1/categories/".$data['cat_id']."/");
                
                $json = '{}';
                $response_type = "DELETE";
                break;
            
            default:
                # code...
                break;
        }
        
        
        $curlOptions = array(
        CURLOPT_CUSTOMREQUEST => $response_type,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
        );
        
        curl_setopt_array( $ch, $curlOptions );
        
        $response = curl_exec( $ch );

        return $response;
    }
}
