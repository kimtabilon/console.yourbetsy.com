<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Items;

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
                foreach ($json_data as $key => $value) {
                    $query = 'SELECT id, sku, quantity FROM `items` 
                            WHERE sku = '."'".$value->sku."'".'';
                    $consolebetsy_item = DB::select($query);
                    if ($consolebetsy_item) {
                        if ($consolebetsy_item[0]->quantity != $value->qty) {
                            $update_qty = Items::find($consolebetsy_item[0]->id);
                            $update_qty->quantity = $value->qty;
                            $update_qty->save();
                        }
                    }
                }
                echo 'syncing DONE';
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
}
