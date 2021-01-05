<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Orders;
use App\OrderStatuses;
use App\OrderItems;
use Storage;
use Carbon\Carbon;

class Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:order {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        $token_details = storeToken();
        switch ($action) {
            case 'sync-processing':
                $this->log('syncing of processing order started');
                $existing_order = OrderStatuses::select('order_id')->where('status','Processing')
                ->pluck('order_id')->toArray();

                $orders = $this->get_order_bystatus('processing');
                $this->log('number of processing order from store: '.count($orders['items']));
                $new_order = 0;
                $existing_order = 0;
                for ($i=0; $i < count($orders['items']); $i++) { 
                    $order_details = $orders['items'][$i];
                    // dd($order_details);
                    if (!in_array($order_details['increment_id'],$existing_order)) {
                        $order_addreses = $this->get_order_addresses($order_details['billing_address']['parent_id']);
                        $shipping_address = json_encode([]);
                        $billing_address = json_encode([]);
                        foreach ($order_addreses as $key => $value) {
                            if ($value['address_type'] == "shipping") {
                                $shipping_address = json_encode($value);
                            }else if($value['address_type'] == "billing") {
                                /* dd($value); */
                                $billing_address = json_encode($value);
                            }
                        }
                        /* dd($order_details['created_at']); */
                        /* dd(json_encode($shipping_address)); */
                        /* ORDER TABLE */
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
                        $db_order->total_refunded = isset($order_details['total_refunded'])? $order_details['total_refunded'] : 0;
                        
                        $db_order->save();

                        $db_order_statuses = New OrderStatuses;
                        $db_order_statuses->order_id =  $order_details['increment_id'];
                        $db_order_statuses->status =  $order_details['status'];
                        $db_order_statuses->save();

                        
                        foreach ($order_details['items'] as $item_v) {
                            $item_status = '';
                            if ($item_v['qty_refunded'] > 0) {
                                $item_status = 'refunded';
                            }elseif ($item_v['qty_canceled'] > 0) {
                                $item_status = 'canceled';
                            }else if($item_v['qty_invoiced'] > 0 && $item_v['qty_canceled'] == 0 && $item_v['qty_shipped'] == 0 && $item_v['qty_refunded'] == 0) {
                                $item_status = 'processing';
                            }else if($item_v['qty_shipped'] > 0) {
                                $item_status = 'shipped';
                            }else if($item_v['qty_invoiced'] == 0 && $item_v['qty_canceled'] == 0 && $item_v['qty_shipped'] == 0 && $item_v['qty_refunded'] == 0) {
                                $item_status = 'pending';
                            }

                            $db_order_items = New OrderItems;
                            $db_order_items->order_id = $order_details['increment_id'];
                            $db_order_items->order_item_id = $item_v['item_id'];
                            $db_order_items->sku = $item_v['sku'];
                            $db_order_items->name = $item_v['name'];
                            $db_order_items->price = $item_v['price'];
                            $db_order_items->orginal_price = $item_v['original_price'];
                            $db_order_items->qty_canceled = $item_v['qty_canceled'];
                            $db_order_items->qty_invoiced = $item_v['qty_invoiced'];
                            $db_order_items->qty_ordered = $item_v['qty_ordered'];
                            $db_order_items->qty_refunded = $item_v['qty_refunded'];
                            $db_order_items->qty_shipped = $item_v['qty_shipped'];
                            $db_order_items->row_total = $item_v['row_total'];
                            $db_order_items->tax_amount = $item_v['tax_amount'];
                            $db_order_items->tax_percent = $item_v['tax_percent'];
                            $db_order_items->discount_amount = $item_v['discount_amount'];
                            $db_order_items->amount_refunded = $item_v['amount_refunded'];
                            $db_order_items->product_id = $item_v['product_id'];
                            $db_order_items->store_id = $item_v['store_id'];
                            $db_order_items->status = $item_status;
                            $db_order_items->save();
                        }
                        $new_order++;
                    }else{
                        foreach ($order_details['items'] as $item_v) {
                            $item_status = '';
                            if ($item_v['qty_refunded'] > 0) {
                                $item_status = 'refunded';
                            }elseif ($item_v['qty_canceled'] > 0) {
                                $item_status = 'canceled';
                            }else if($item_v['qty_invoiced'] > 0 && $item_v['qty_canceled'] == 0 && $item_v['qty_shipped'] == 0 && $item_v['qty_refunded'] == 0) {
                                $item_status = 'processing';
                            }else if($item_v['qty_shipped'] > 0) {
                                $item_status = 'shipped';
                            }else if($item_v['qty_invoiced'] == 0 && $item_v['qty_canceled'] == 0 && $item_v['qty_shipped'] == 0 && $item_v['qty_refunded'] == 0) {
                                $item_status = 'pending';
                            }

                            $check_exist_orderitem = OrderItems::where('order_id', $order_details['increment_id'])
                                                    ->where('sku',$item_v['sku'])
                                                    ->where('status', '!=' ,'shipped')->first();

                            
                            if (isset($check_exist_orderitem->id)) {
                                $update_orderitem = OrderItems::find($check_exist_orderitem->id);
                                $update_orderitem->qty_canceled = $item_v['qty_canceled'];
                                $update_orderitem->qty_invoiced = $item_v['qty_invoiced'];
                                $update_orderitem->qty_ordered = $item_v['qty_ordered'];
                                $update_orderitem->qty_refunded = $item_v['qty_refunded'];
                                $update_orderitem->qty_shipped = $item_v['qty_shipped'];
                                $update_orderitem->status = $item_status;
                                $update_orderitem->save();
                            }
                        }

                        $existing_order++;
                    }
                    
                }
                $this->log('number of new orders under processing: '.$new_order);
                $this->log('number of existing orders changing status: '.$existing_order);
                $this->log('syncing of processing order done');
                break;
            /* case 'sync-complete':
                $order = $this->get_order_bystatus('complete');
                if ($order['items']) {
                    $this->save_order_details($order['items'], 'complete');
                }
                
                break; */
            /* case 'sync-canceled':
                $order = $this->get_order_bystatus('canceled');
                if ($order['items']) {
                    $this->save_order_details($order['items'], 'canceled');
                }
                break;
            case 'sync-hold':
                $order = $this->get_order_bystatus('holded');
                if ($order['items']) {
                    $this->save_order_details($order['items'], 'holded');
                }
                break; */
            default:
                # code...
                break;
        }
    }

    public function get_order_bystatus($status) {
        $token_details = storeToken();
        $ch = curl_init($token_details['domain']."/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=".$status."&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            /* CURLOPT_POSTFIELDS => $json, */
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_list = curl_exec( $ch );
        $orders = json_decode($order_list, true);

        /* dd($orders); */
        return $orders;
        
    }

    public function save_order_details($order, $status) {
        $existing_order = Orders::select('order_id')->where('status',$status)
                ->pluck('order_id')->toArray();
        for ($i=0; $i < count($order); $i++) { 
            $order_details = $order[$i];
            // dd($order_details);
            if (!in_array($order_details['increment_id'],$existing_order)) {
                $order_addreses = $this->get_order_addresses($order_details['billing_address']['parent_id']);
                $shipping_address = json_encode([]);
                $billing_address = json_encode([]);
                foreach ($order_addreses as $key => $value) {
                    if ($value['address_type'] == "shipping") {
                        $shipping_address = json_encode($value);
                    }else if($value['address_type'] == "billing") {
                        /* dd($value); */
                        $billing_address = json_encode($value);
                    }
                }
                /* ORDER TABLE */
                /* $db_order = New Orders;
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
                $db_order->save(); */


                $order_status_details = OrderStatuses::where('order_id', $order_details['increment_id'])->first();
                // dd($order_status_details);
                if (isset($order_status_details->id)) {
                    $order_status_update = OrderStatuses::find($order_status_details->id);
                    $order_status_update->status = $order_details['status'];
                    $order_status_update->save();
                }else{
                    $db_order_statuses = New OrderStatuses;
                    $db_order_statuses->order_id =  $order_details['increment_id'];
                    $db_order_statuses->status =  $order_details['status'];
                    $db_order_statuses->save();
                }
                
                foreach ($order_details['items'] as $item_v) {
                    $order_item_exist = OrderItems::where('order_id', $order_details['increment_id'])
                        ->where('sku', $item_v['sku'])->first();
                    if (!isset($order_item_exist->id)) {
                        $db_order_items = New OrderItems;
                        $db_order_items->order_id = $order_details['increment_id'];
                        $db_order_items->order_item_id = $item_v['item_id'];
                        $db_order_items->sku = $item_v['sku'];
                        $db_order_items->name = $item_v['name'];
                        $db_order_items->price = $item_v['price'];
                        $db_order_items->orginal_price = $item_v['original_price'];
                        $db_order_items->qty_canceled = $item_v['qty_canceled'];
                        $db_order_items->qty_invoiced = $item_v['qty_invoiced'];
                        $db_order_items->qty_ordered = $item_v['qty_ordered'];
                        $db_order_items->qty_refunded = $item_v['qty_refunded'];
                        $db_order_items->qty_shipped = $item_v['qty_shipped'];
                        $db_order_items->row_total = $item_v['row_total'];
                        $db_order_items->tax_amount = $item_v['tax_amount'];
                        $db_order_items->tax_percent = $item_v['tax_percent'];
                        $db_order_items->discount_amount = $item_v['discount_amount'];
                        $db_order_items->amount_refunded = $item_v['amount_refunded'];
                        $db_order_items->save();
                    }
                }
                
            }
        }
    }

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

    public function test($order_id) {
        $token_details = storeToken();
        /* $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id.'/addresses/shipping'); */
        $ch = curl_init($token_details['domain']."/rest/V1/orders/".$order_id);
        $curlOptions = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array( "Content-type: application/json", "Authorization: Bearer ".json_decode($token_details['token']))
            );
            
        curl_setopt_array( $ch, $curlOptions );
            
        $order_data = curl_exec( $ch );
        $order_data = json_decode($order_data, true);
        return $order_data;
    }

    protected function log($text) {
        $text = Carbon::now()->toDateTimeString().' >> '.$text;
        Storage::prepend('public/logs/cron.log', $text);
    }
}
