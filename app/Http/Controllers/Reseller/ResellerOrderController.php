<?php

namespace App\Http\Controllers\Reseller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Items;
use App\ResellersProfiles;
use App\Orders;
use App\OrderItems;
use App\OrderStatuses;
use App\OrderShipments;
use App\OrderShipmentItems;
use App\OrderShipmentTracks;

class ResellerOrderController extends Controller
{
    public function index() {
        /* $this->get_orders_from_store(); */
        /* $this->order_cancel(20); */
        /* $this->test(); */
        /* $this->ship_order(); */
        /* $this->get_orders_from_store(); */
        
        $skus = $this->get_sellers_sku();
        $order_items = OrderItems::select('order_id','status')
                    ->whereIn('sku',$skus)
                    ->get()->toArray();
        $item_order_id = [];
        foreach ($order_items as $value) {
            if (!array_key_exists($value['order_id'],$item_order_id)) {
                $statuses = [];
                foreach ($order_items as $value_2) {
                    if ($value['order_id'] == $value_2['order_id']) {
                        $statuses[] = $value_2['status'];
                    }
                }
                $item_order_id[$value['order_id']] = $statuses;
            }
        }
        $data = [];
        foreach ($item_order_id as $key => $statuses) {
            $status = '';
            /* if (in_array('shipped',$statuses)) {
                $status = 'shipped';
            }elseif (in_array('processing',$statuses)) {
                if ($key == "000000030") {
                    dd($status);
                }
                $status = 'processing';
            }elseif (in_array('pending',$statuses)) {
                $status = 'pending';
            }elseif (in_array('canceled',$statuses)) {
                $status = 'canceled';
            } */
            if (in_array('pending',$statuses)) {
                $status = 'pending';
            }elseif (in_array('processing',$statuses)) {
                $status = 'processing';
            }elseif (in_array('shipped',$statuses)) {
                $status = 'complete';   
            }elseif (in_array('refunded',$statuses)) {
                $status = 'complete';
            }elseif (in_array('canceled',$statuses)) {
                $status = 'canceled';
            }

            
            $order_details = Orders::select('customer_firstname', 'customer_lastname', 'customer_email', 'date_ordered')
                            ->where('order_id', $key)->first();
            $row = [];
            $row['order_id'] = $key;
            $row['customer_firstname'] = $order_details->customer_firstname;
            $row['customer_lastname'] = $order_details->customer_lastname;
            $row['customer_email'] = $order_details->customer_email;
            $row['date_ordered'] = $order_details->date_ordered;
            $row['status'] = $status;

            $data[] = $row;
        }
        /* dd($data);
        $order_status = OrderStatuses::order_status_list($order_items);
        $data = [];
        foreach ($order_status as $value) {
            $order_details = Orders::order_by_status($value);

            $data[] = $order_details;
        } */

        return view('reseller.reseller-order',['data' => $data]);
    }

    public function get_sellers_sku() {
        $reseller = Auth::user()->profile;
        $seller_allowed = [];
        if ($reseller->reseller_position == 1) {
            $seller_allowed = [$reseller->id,$reseller->parent];
        }else{
            $sellers_second_users = ResellersProfiles::select('username_id')
                                    ->where('parent',$reseller->id)
                                    ->pluck('username_id')->toArray();
            $seller_allowed = $sellers_second_users;
            array_push($seller_allowed, $reseller->username_id);
        }
        $skus = Items::select('sku')
                ->whereIn('username_id',$seller_allowed)
                ->pluck('sku')->toArray();

        return $skus;
    }

    public function get_orders_from_store() {
        $token_details = storeToken();
        /* echo $token_details['token'];
        dd($token_details['token']); */
        // $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria=all");
        $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=complete&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/000000002"); */

        /* $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=pending&searchCriteria[sortOrders][0][field]=increment_id&fields=items[increment_id,entity_id]"); */
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria=all"); */
        /* $json  = json_encode([]); */
            
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            /* CURLOPT_POSTFIELDS => $json, */
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_list = curl_exec( $ch );
        
        $orders = json_decode($order_list, true);
        dd($orders);
            /* dd($orders['items']); */
        $order_data = [];
        for ($i=0; $i < count($orders['items']); $i++) { 
            $order_details = $orders['items'][$i];
            /* dd($orders['items']); */
            $sku_exist = 0;
            for ($ii=0; $ii < count($order_details['items']); $ii++) { 
                $order_sku = $orders['items'][$i]['items'][$ii]['sku'];
                if (in_array($order_sku, $console_skus)) {
                    $sku_exist++;
                }
            }
            if ($sku_exist > 0) {
                $order_data[] = $order_details;
            }
        }

        /* $result = $this->order_details($order_id_list); */
        return $order_data;
        /* dd($order_data); */
    }

    public function order_details($parent_id) {
        /* 
            $parent_id is from payment
        */
        $token_details = storeToken();
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id.'/addresses/shipping'); */
        $ch = curl_init($token_details['domain']."/rest/V1/orderAddresses?searchCriteria[filter_groups][0][filters][0][field]=parent_id&searchCriteria[filter_groups][0][filters][0][value]=".$parent_id."&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_data = curl_exec( $ch );
        $order_data = json_decode($order_data, true);
        dd($order_data);
        return $order_data;
        
    }

    public function test_2() {
        /* dd('test'); */
        /* 
            $parent_id is from payment
        */
        $token_details = storeToken();
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id.'/addresses/shipping'); */
        $ch = curl_init($token_details['domain']."/rest/V1/orders/14");
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_data = curl_exec( $ch );
        $order_data = json_decode($order_data, true);

        
        dd($order_data);
        return $order_data;
        
    }

    public function V2_ship_order() {
        $order_status = OrderStatuses::select('order_id','status')
            ->where('order_id',request('order_id'))->first();
        $order_details = Orders::order_by_status($order_status);
        $order_items = OrderItems::select('order_item_id', 'qty_ordered')
                ->where('order_id',request('order_id'))->get();
        
        $items = array();
        foreach ($order_items as $key => $value) {
            $items[] = ['order_item_id' => $value->order_item_id,'qty' => $value->qty_ordered];
        }

        $token_details = storeToken();
        $order_id = (int)$order_details->entity_id;
        $url = $token_details['domain'].'/rest/V1/shipment';
        $ch = curl_init($url);



        /* [
            "items"=> [
              [
                "extension_attributes"=> [],
                "order_item_id"=> 6,
                "qty"=> 1
              ]
            ],
            "notify"=> true,
            "appendComment"=> true,
            "comment"=> [
              "extension_attributes"=> [],
              "comment"=> "Your order has been shipped, login to view tracking information.",
              "is_visible_on_front"=> 1
            ],
            "tracks"=> [
              [
                "extension_attributes"=> [],
                "track_number"=> "3SCEMW182389201",
                "title"=> "Netherlands Post Ground Parcel",
                "carrier_code"=> "fixed"
              ]
            ],
            "packages"=> [
              [
                "extension_attributes"=> []
              ]
            ],
            "arguments"=> [
              "extension_attributes"=> []
            ]
          ] */

        $json = [
            'entity' => [
                'order_id' => $order_id,
                'items' => $items,
                "notify"=> true,
                'tracks' => [
                    [
                        'parent_id' => 15,
                        'description' => 'description',
                        'order_id' => $order_id,
                        'qty' => 2,
                        'weight' => 00,
                        'track_number' => 000,
                        'title' => 'custom',
                        'carrier_code' => 'custom'
                    ]
                ]
            ]
        ];
        // dd(json_encode($json));
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_list = curl_exec( $ch );
        dd($order_list);
    }

    public function ship_order() {
        /* dd('test'); */
        $skus = json_decode(request('skus'));
        /* dd($skus);
        dd(request()->all()); */
        // $test = '"1235"';
        /* $test = '"{"message": {}}"';
        $test = json_decode($test);
        $test = (int)$test;
        echo $test;
        dd($test); */
        /* dd(request()->all()); */
        /* $order_details = Orders::order_list([request('order_id')]); */
        $order_status = OrderStatuses::select('order_id','status','id')
            ->where('order_id',request('order_id'))->first();
        $order_details = Orders::order_by_status($order_status);
        $order_items = OrderItems::select('order_item_id', 'qty_invoiced', 'qty_shipped', 'id')
                ->whereIn('order_id',[request('order_id')])
                ->whereIn('sku',$skus)
                ->get();
        
        
        $token_details = storeToken();
        $order_id = (int)$order_details->entity_id;
        $url = $token_details['domain'].'/rest/V1/order/'.$order_id.'/ship';
        $ch = curl_init($url);
        
        $items = array();
        foreach ($order_items as $key => $value) {
            $items[] = ['order_item_id' => $value->order_item_id,'qty' => $value->qty_invoiced];
            
            /* $items[] = array(
                "order_item_id" =>$value->order_item_id, 
                "qty" => $value->qty_ordered
            ); */
            /* $row = new \stdClass;
            $row->order_item_id = $value->order_item_id;
            $row->qty = $value->qty_ordered;
            $items[] = $row; */
            
        }
        /* dd($items); */
        /* WORKING JSON */
        $json = [ 
            "orderId" => $order_id,
            "items"=> $items, 
            /* "notify" => true, */ 
            /* "appendComment" => false,  */
            /* "comment" => [ 
                "extension_attributes" => [], 
                "comment" => "Item(s) has been shipped", 
                "is_visible_on_front" => 0 
            ],  */
            "tracks" => [ 
                [ 
                    /* "extension_attributes" => [], */ 
                    "track_number" => request('tracking_number'), 
                    "title" => 'custom', 
                    "carrier_code" => 'custom' 
                    ] 
            ] 
        ];
        /* dd(json_encode($json)); */
        /* $json = [
            
                'items' => [
                    [
                        'order_item_id' => 17,
                        'qty' => 1
                    ],
                    [
                        'order_item_id' => 18,
                        'qty' => 1
                    ],
                ]

        ]; */
        /* $json = [
            'items' => [
                [
                    'qty' => 1,
                    'order_item_id' => 20
                    
                ],
                [
                    'qty' => 1,
                    'order_item_id' => 21
                    
                ],
            ],
            'notify' => true,
            'appendComment' => false,
            'tracks' => [
                [
                    'track_number' => 1234,
                    'title' => 'UPS',
                    'carrier_code' => 'ups',
                ]
                
            ]
        ]; */
        /* $json = [
            "items" => $items,
            "notify" => true,
            "comment" => [
                "comment" => "test"
            ],
            "arguments" => [
                "extension_attributes" => [
                    "source_code" => "reno_wh"
                ]
            ]
        ]; */
        /* $json = [
                "entity" => [
                    "order_id"=> $order_id,
                    "items" => [
                        [
                            "order_item_id" => 13,
                            "qty" => 2
                        ],
                        [
                            "order_item_id" => 14,
                            "qty" => 3
                        ],
                    ]
                ]
        ]; */
        /* $json = json_encode($json);
        dd($json); */
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );    
        $res = curl_exec( $ch );
        
        $res = json_decode($res);
        $err = '';
        $status = '';
        if(!is_object($res)) {
            
            $status = 'success';
            $res = (int)$res;
            $this->get_shipment_details($res, request('order_id'));
            /* UPDATE STATUS */
            /* $this->get_order_byID($order_id); */
            $all_items_shipped = $this->update_orders(request('order_id'),$order_items,'shipped');
            if ($all_items_shipped == 0) {
                $update_order_status = OrderStatuses::find($order_status->id);
                $update_order_status->status = "complete";
                $update_order_status->save();
            }
            
        }else{
            $err = $res;
            $status = 'error';
        }
        return response()->json(["status" => $status,"errors" => $err], 200);
    }

    public function get_shipment_details($ship_id,$inc_order_id) {
        $token_details = storeToken();
        $url = $token_details['domain'].'/rest/V1/shipment/'.$ship_id;
        $ch = curl_init($url);

        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $res = curl_exec( $ch );
        $res = json_decode($res, true);
        /* ORDER SHIPMENT */
        $shipmennt = New OrderShipments;
        $shipmennt->ship_id = $ship_id;
        $shipmennt->increment_id = $res['increment_id'];
        $shipmennt->increment_order_id = $inc_order_id;
        $shipmennt->order_id = $res['order_id'];
        $shipmennt->shipping_address_id = $res['shipping_address_id'];
        $shipmennt->billing_address_id = $res['billing_address_id'];
        $shipmennt->ship_date = $res['created_at'];
        $shipmennt->total_qty = $res['total_qty'];
        $shipmennt->save();

        /* SHIPMENT ITEM */
        foreach ($res['items'] as $value_i) {
            $s_item = New OrderShipmentItems;
            $s_item->ship_id = $value_i['parent_id'];
            $s_item->entity_id = $value_i['entity_id'];
            $s_item->order_id = $value_i['order_item_id'];
            $s_item->product_id = $value_i['product_id'];
            $s_item->sku = $value_i['sku'];
            $s_item->qty = $value_i['qty'];
            $s_item->weight = $value_i['weight'];
            $s_item->save();
        }

        /* SHIPMENT TRACK */
        foreach ($res['tracks'] as $value_t) {
            $s_track = New OrderShipmentTracks;
            $s_track->entity_id = $value_t['entity_id'];
            $s_track->ship_id = $value_t['parent_id'];
            $s_track->order_id = $value_t['order_id'];
            $s_track->track_date_added = $value_t['created_at'];
            $s_track->description = isset($value_t['description'])?$value_t['description'] : '';
            $s_track->track_number = $value_t['track_number'];
            $s_track->title = $value_t['title'];
            $s_track->carrier_code = $value_t['carrier_code'];
            $s_track->weight = isset($value_t['weight'])?$value_t['weight'] : 0;
            $s_track->qty = isset($value_t['qty'])?$value_t['qty'] : 0;
            $s_track->save();
        }
    }

    public function update_orders($order_id, $order_items, $item_status) {

        

        foreach ($order_items as $key => $value) {
            
            $update_orderitem = OrderItems::find($value->id);
            if ($item_status == "shipped") {
                $update_orderitem->qty_shipped = $value->qty_invoiced;
            }else if($item_status == "refunded"){
                $update_orderitem->qty_refunded = $value->qty_invoiced;
            }
            
            $update_orderitem->status = $item_status;
            $update_orderitem->save();
        }
        $check_orderitem_status = OrderItems::where('order_id',$order_id)
                        ->whereIn('status',['pending', 'processing'])
                        ->first();
        $count_not_shipped = 0;
        if (isset($check_orderitem_status->id)) {
            $count_not_shipped++;
        }
        /* $count_not_shipped = 0;
        foreach ($check_orderitem_status as $val) {
            if ($val->status != $item_status) {
                $count_not_shipped++;
            }
        } */

        return $count_not_shipped;
    }

    /* public function get_order_byID($order_id) {
        $order_exist = Orders::where('entity_id', $order_id)->where('status','complete')->first();
        if (!isset($order_exist->id)) {
            $token_details = storeToken();
            // $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=parent_id&searchCriteria[filter_groups][0][filters][0][value]=".$order_id."&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
            $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id);

            $curlOptions = array(
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
                );
                
            curl_setopt_array( $ch, $curlOptions );
            $res = curl_exec( $ch );
            $res = json_decode($res, true);
            $order_details = $res;
            
            $order_addreses = $this->get_order_addresses($order_details['billing_address']['parent_id']);
            $shipping_address = json_encode([]);
            $billing_address = json_encode([]);
            foreach ($order_addreses as $key => $value) {
                if ($value['address_type'] == "shipping") {
                    $shipping_address = json_encode($value);
                }else if($value['address_type'] == "billing") {
                    $billing_address = json_encode($value);
                }
            }
            $db_order = New Orders;
            $db_order->order_id = $order_details['increment_id'];
            $db_order->entity_id = $order_details['entity_id'];
            $db_order->date_ordered = $order_details['created_at'];
            $db_order->customer_email = $order_details['customer_email'];
            $db_order->customer_firstname = isset($order_details['customer_firstname'])? $order_details['customer_firstname']: '';
            $db_order->customer_lastname = isset($order_details['customer_lastname'])? $order_details['customer_lastname']: '';
            $db_order->shipping_address = $shipping_address;
            $db_order->billing_address = $billing_address;
            $db_order->payment_information = json_encode($order_details['payment']);
            $db_order->shipping_description = $order_details['shipping_description'];
            $db_order->shipping_incl_tax = isset($order_details['shipping_incl_tax'])? $order_details['shipping_incl_tax'] : 0;
            $db_order->shipping_invoiced = isset($order_details['shipping_invoiced'])? $order_details['shipping_invoiced'] : 0;
            $db_order->grand_total = isset($order_details['grand_total'])? $order_details['grand_total'] : 0;
            $db_order->total_paid = isset($order_details['total_paid'])? $order_details['total_paid'] : 0;
            $db_order->total_due = isset($order_details['total_due'])? $order_details['total_due'] : 0;
            $db_order->status = $order_details['status'];
            $db_order->save();
        }
        
    } */

    public function get_order_addresses($parent_id) {
        $token_details = storeToken();
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id.'/addresses/shipping'); */
        $ch = curl_init($token_details['domain']."/rest/V1/orderAddresses?searchCriteria[filter_groups][0][filters][0][field]=parent_id&searchCriteria[filter_groups][0][filters][0][value]=".$parent_id."&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_data = curl_exec( $ch );
        $order_data = json_decode($order_data, true);
        return $order_data['items'];
    }

    public function order_cancel($order_id) {
        $token_details = storeToken();
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id.'/addresses/shipping'); */
        $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id."/cancel");
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_data = curl_exec( $ch );
        $order_data = json_decode($order_data, true);
        dd($order_data);
        return $order_data['items'];
    }

    public function order_items() {
        $skus = $this->get_sellers_sku();
        
        $order_items = OrderItems::whereIn('order_id',[request('order_id')])
                        ->whereIn('sku',$skus)
                        ->get();
        return response()->json($order_items, 200);
    }

    public function refund_order() {
        $skus = json_decode(request('skus'));
        /* dd($skus); */
        $order_status = OrderStatuses::select('order_id','status','id')
            ->where('order_id',request('order_id_cancel'))->first();
        $order_details = Orders::order_by_status($order_status);
        $order_items = OrderItems::select('order_item_id', 'qty_invoiced', 'qty_shipped', 'id')
                ->whereIn('order_id',[request('order_id_cancel')])
                ->whereIn('sku',$skus)
                ->get();
        $order_id = (int)$order_details->entity_id;
        $item_product_id = [];
        $items = [];
        foreach ($order_items as $key => $value) {
            $items[] = ['order_item_id' => $value->order_item_id,'qty' => $value->qty_invoiced];
            $item_product_id[] = $value->order_item_id;
        }
        $token_details = storeToken();

        $ch = curl_init($token_details['domain']."/rest/V1/order/".$order_id."/refund");

        $json = [
            "items" => $items,
            "notify" => true,
            "arguments" => [
                "shipping_amount" => 0,
                "adjustment_positive" => 0,
                "adjustment_negative" => 0,
                "extension_attributes" => [
                    "return_to_stock_items" => $item_product_id
                ]
            ]
        ];
        /* dd(json_encode($json)); */
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
        );
            
        curl_setopt_array( $ch, $curlOptions );
        $res = curl_exec( $ch );
        $res = json_decode($res);
        /* dd($res); */
        $err = '';
        $status = '';
        if(is_array($res) != 1) {
            $status = 'success';
            /* UPDATE STATUS */
            $all_items_shipped = $this->update_orders(request('order_id_cancel'),$order_items,'refunded');
            if ($all_items_shipped == 0) {
                $update_order_status = OrderStatuses::find($order_status->id);
                $update_order_status->status = "complete";
                $update_order_status->save();
            }
            
        }else{
            $err = $res;
            $status = 'error';
        }
        return response()->json(["status" => $status,"errors" => $err], 200);
    }

    public function view_order_details() {
        $skus = $this->get_sellers_sku();

        $details = array();
        $details['order_details'] = Orders::whereIn('order_id',[request('order_id')])
                        ->get();

        $details['order_items'] = OrderItems::whereIn('order_id',[request('order_id')])
        ->whereIn('sku',$skus)
        ->get();

        $statuses = [];
        foreach ($details['order_items'] as $value) {
            $statuses[] = $value->status;
        }

        if (in_array('pending',$statuses)) {
            $details['stat'] = 'pending';
        }elseif (in_array('processing',$statuses)) {
            $details['stat'] = 'processing';
        }elseif (in_array('shipped',$statuses)) {
            $details['stat'] = 'complete';   
        }elseif (in_array('refunded',$statuses)) {
            $details['stat'] = 'complete';
        }elseif (in_array('canceled',$statuses)) {
            $details['stat'] = 'canceled';
        }
        $details['order_status'] = OrderStatuses::select('order_id','status','id')
            ->where('order_id',request('order_id'))->first();
        
        return response()->json($details, 200);
    }

    public function send_email_ship($shipment_id) {
        $token_details = storeToken();
        
        $ch = curl_init($token_details['domain']."/rest/V1/shipment/".$shipment_id."/emails");

        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
        $res = curl_exec( $ch );
        /* $res = json_decode($res, true); */
        dd($res);
        
    }
}
