<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Storage;
use App\Items;
use Carbon\Carbon;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:run {action}';

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

        $betsy_api = "https://www.yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = $this->cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);
        switch ($action) {
            case 'qty':
                $this->log('sync qty started');
                $this->log('number of items at store: '.count($json_data));
                $qty_sync_count = 0;
                foreach ($json_data as $key => $value) {
                    $query = 'SELECT id, sku, quantity FROM `items` 
                            WHERE sku = '."'".$value->sku."'".'';
                    $consolebetsy_item = DB::select($query);
                    if ($consolebetsy_item) {
                        if ($consolebetsy_item[0]->quantity != $value->qty) {
                            $qty_sync_count++;
                            $update_qty = Items::find($consolebetsy_item[0]->id);
                            $update_qty->quantity = $value->qty;
                            $update_qty->save();
                        }
                    }
                }
                $this->log('number of items qty sync: '.$qty_sync_count);
                $this->log('sync qty done');
                break;

            case 'manufacturer':
                $token_details = storeToken();

                $this->log('sync manufacturer started');

                $manufacturer_sync_count = 0;

                foreach ($json_data as $key => $value) {
                    $query = 'SELECT  items.sku, resellers_profiles.reseller_name FROM `items` 
                        JOIN `resellers_profiles` ON items.username_id = resellers_profiles.username_id
                        WHERE sku = '."'".$value->sku."'".'';
                    $consolebetsy_item = DB::select($query);
                    if ($consolebetsy_item) {
                        $console_manufacturer = $consolebetsy_item[0]->reseller_name;
                        if ($console_manufacturer != $value->manufacturer) {
                            $manufacturer_sync_count++;
                            $ch = curl_init($token_details['domain']."/rest/all/V1/products/".$value->sku);
                            $json = new \stdClass;
                            $json->product = [
                                "custom_attributes" => [
                                    [
                                        "attribute_code" => "manufacturer",
                                        "value" => $console_manufacturer
                                    ],
                                ]
                            ];

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

                $this->log('number of manufacturer sync: '.$manufacturer_sync_count);
                $this->log('sync manufacturer done');
                break;
            
            default:
                # code...
                break;
        }
    }


    protected function cleanJson($json) {
        $json = str_replace('"[', '[', $json);
        $json = str_replace(']"', ']', $json);
        $json = html_entity_decode($json);
        $json = stripslashes($json);
        return $json;
    }

    protected function log($text) {
        $text = Carbon::now()->toDateTimeString().' >> '.$text;
        Storage::prepend('public/logs/cron.log', $text);
    }
}
