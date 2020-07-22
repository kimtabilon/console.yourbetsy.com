<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Items;

class ExportQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportQuantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export quantity';

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
        $betsy_api = "https://yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = $this->cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $column = ['entity_id','qty'];
        foreach (range('A', 'B') as $ltr) {
            foreach ($column as $key => $col) {
                $sheet->setCellValue($ltr.'1', $col);
            }
        }

        $mismatch_quantity_count = 0;

        $row_count = 2;
        foreach ($json_data as $key => $value) {
            $consolebetsy_item = Items::where('sku',$value->sku)->first();
            if ($consolebetsy_item) {
                if ($consolebetsy_item->quantity !== $value->qty) {

                    $sheet->setCellValue('A'.$row_count, $value->entity_id);
                    $sheet->setCellValue('B'.$row_count, $consolebetsy_item->quantity);
        
                    $row_count++;
                    $mismatch_quantity_count++;
                }
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->save('./storage/app/public/export/qty.csv');

        echo "total mismatch item quantity: ".$mismatch_quantity_count;
    }

    protected function cleanJson($json) {
        $json = str_replace('"[', '[', $json);
        $json = str_replace(']"', ']', $json);
        $json = html_entity_decode($json);
        $json = stripslashes($json);
        return $json;
    }
}
