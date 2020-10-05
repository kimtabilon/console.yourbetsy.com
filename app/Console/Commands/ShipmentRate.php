<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib\Net\SFTP;
use Storage;

class ShipmentRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:shipmentrate {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shipment Rate';

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
        switch ($action) {
            case 'generate-import':

                Storage::deleteDirectory("/public/tablerate/forimport");
                
                $files = public_path('storage/tablerate/');
                $files = scandir($files);
                $files = array_diff($files, array('.', '..'));
                
                $tablerateimportDIR = "public/tablerate/forimport";
                $check_tablerateDIR_exist = Storage::disk('local')->exists($tablerateimportDIR);
                if (!$check_tablerateDIR_exist) {
                    Storage::makeDirectory($tablerateimportDIR);
                }

                $import_csv = $this->fopen('forimport/import.csv', 'w+');

                $items_generated = 0;
                $item_limit = 25;
                $to_delete_csv = [];
                $test = [];
                foreach ($files as $value) {

                    $items = $this->fopen($value, 'r');
                    $loop = 0;
                    $exess_item = [];
                    while (! feof($items)) {
                        $loop++;
                        $item = fgetcsv($items);

                        if($loop > 1 && $item[0] != '') {
                            $test[] = $item;
                            
                            
                            if ($item_limit != $items_generated) {
                                $items_generated++;
                                fputcsv($import_csv, $item);
                            }else{
                                $exess_item[] = $item;
                            }
                        }
                    }
                    
                    fclose($items);
                    if ($item_limit == $items_generated) {
                        $exess = $this->fopen($value, 'w+');
                        fputcsv($exess, [
                            'Country','Region','ZIP Code','SKU','Shipping Price'
                        ]);
                        foreach ($exess_item as $value) {
                            fputcsv($exess, $value);
                        }
                        
                        fclose($exess);
                        break;
                    }else{
                        $to_delete_csv[] = $value;
                    }
                }
                fclose($import_csv);
                
                foreach ($to_delete_csv as $val) {
                    Storage::delete("/public/tablerate/".$val);
                }


                /* $url = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/tableratesku-import";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                $exe_result = curl_exec($ch);
                $exe_err = curl_errno($ch);
                curl_close($ch); */
                
                /* foreach ($to_delete_csv as $val) {
                    Storage::delete("/public/tablerate/".$val);
                }
                Storage::deleteDirectory("/public/tablerate/forimport");
                
                dd($to_delete_csv); */
                break;
            
            default:
                # code...
                break;
        }
    }

    protected function fopen($file, $mode) {
        if(!file_exists(public_path('storage/tablerate/'.$file))) {
            Storage::put('public/tablerate/'.$file, '');
        }
        return fopen(public_path('storage/tablerate/'.$file), $mode);
    }
}
