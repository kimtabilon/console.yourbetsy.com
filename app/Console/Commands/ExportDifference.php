<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Summary;

/* use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; */

use phpseclib\Net\SFTP;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Items;
use App\ItemsCategories;

class ExportDifference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:run {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export CSV File,commandlist=[price,qty,new]';

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

        $row_count = 2;

        switch ($action) {
            case 'price':
                $csvfile = $this->fopen("price.csv", 'w+');
                fputcsv($csvfile, [
                    'entity_id','price','msrp','msrp2',
                    'special_price','special_price_from_date','special_price_to_date'
                ]);

                foreach ($json_data as $key => $value) {
                    $consolebetsy_item = Items::where('sku',$value->sku)->first();
                    if ($consolebetsy_item) {
                        if ($consolebetsy_item->price !== $value->price) {
                            $entity_id = $value->entity_id;
                            $price = $consolebetsy_item->price;
                            $special_price = $consolebetsy_item->special_price;
                            fputcsv($csvfile, [ $entity_id, $price, 0, 0, $special_price, '','' ]);
                        }
                    }
                }
                fclose($csvfile);
                break;
            
            case 'qty':
                

                $csvfile = $this->fopen("qty.csv", 'w+');
                fputcsv($csvfile, ['entity_id','qty']);

                foreach ($json_data as $key => $value) {
                    $consolebetsy_item = Items::where('sku',$value->sku)->first();
                    if ($consolebetsy_item) {
                        if ($consolebetsy_item->quantity !== $value->qty) {
                            $entity_id = $value->entity_id;
                            $quantity = $consolebetsy_item->quantity;
                            fputcsv($csvfile, [ $entity_id, $quantity]);
                        }
                    }
                }
                fclose($csvfile);

                break;

            case 'new':

                $csvfile = $this->fopen("new.csv", 'w+');
                fputcsv($csvfile, [
                    'sku', 'manufacturer', 'price', 'msrp', 'qty', 'name', 'description',
                    'weight', 'map_price', 'special_price', 'rebate_start', 'rebate_end', 'msrp2', 'url_key',
                    'category', 'sub-category'
                ]);

                $consolebetsy_item = Items::with(['items_sub_categories','profile'])->get();
                $url_keys_arr = [];
                $sku_list = [];
                foreach ($json_data as $value_b) {
                    array_push($sku_list, $value_b->sku);
                }

                foreach ($consolebetsy_item as $key => $value_cb) {

                    if (!in_array($value_cb->sku,$sku_list)) {
                        $truncated = Str::limit($value_cb->product_name, 100, '...');
                        $slug_sku = str_replace('+', '-plus', $value_cb->sku);
                        $slug_sku = str_replace('=', '-equal', $slug_sku);
                        $slug_sku = str_replace('?', '-questionmark', $slug_sku);
                        $slug = Str::slug($truncated.' '.$slug_sku, '-');
                        array_push($url_keys_arr,$slug);
                    }
                    /* foreach ($json_data as $value_b) {
                        if ($value_cb->sku !== $value_b->sku) {
                            $truncated = Str::limit($value_cb->product_name, 100, '...');
                            $slug_sku = str_replace('+', '-plus', $value_cb->sku);
                            $slug_sku = str_replace('=', '-equal', $slug_sku);
                            $slug_sku = str_replace('?', '-questionmark', $slug_sku);
                            $slug = Str::slug($truncated.' '.$slug_sku, '-');
                            array_push($url_keys_arr,$slug);
                        }
                    } */
                }
                foreach ($consolebetsy_item as $key => $value_cb) {
                    if (!in_array($value_cb->sku,$sku_list)) {
                        $sku = $value_cb->sku;
                        $manufacturer = $value_cb->profile->reseller_name;
                        $price = $value_cb->price;
                        $quantity = $value_cb->quantity;
                        $product_name = $value_cb->product_name;
                        $product_desc = $value_cb->product_desc;
                        $special_price = $value_cb->special_price;
                        $date_start = str_replace('-', '/', $value_cb->date_start);
                        $date_end = str_replace('-', '/', $value_cb->date_end);
                        
                        $truncated = Str::limit($value_cb->product_name, 100, '...');
                        $slug_sku = str_replace('+', '-plus', $value_cb->sku);
                        $slug_sku = str_replace('=', '-equal', $slug_sku);
                        $slug_sku = str_replace('?', '-questionmark', $slug_sku);
                        $slug = Str::slug($truncated.' '.$slug_sku, '-');

                        if (array_key_exists($slug,$url_keys_arr)) {
                            $slug = $slug.'2';
                        }

                        $sub_category = $value_cb->items_sub_categories->store_subcat_id;

                        $category_details = ItemsCategories::find($value_cb->items_sub_categories->category_id);
                        $category = $category_details->store_cat_id;

                        fputcsv($csvfile, [ $sku, $manufacturer, $price, 0, $quantity, $product_name, $product_desc,
                            0, 0, $special_price, $date_start, $date_end, '', $slug,
                            $category, $sub_category
                        ]);
                    }
                }
                
                fclose($csvfile);
                break;
            
            default:
                # code...
                break;
        }
            

        /* $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->save('./storage/app/public/export/'.$action.'.csv'); */
    }

    protected function fopen($file, $mode) {
        if(!file_exists(public_path('storage/export/'.$file))) {
            Storage::put('public/export/'.$file, '');
        }
        return fopen(public_path('storage/export/'.$file), $mode);
    }

    protected function cleanJson($json) {
        $json = str_replace('"[', '[', $json);
        $json = str_replace(']"', ']', $json);
        $json = html_entity_decode($json);
        $json = stripslashes($json);
        return $json;
    }
}
