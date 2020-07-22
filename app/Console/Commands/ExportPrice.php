<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Items;

class ExportPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export price';

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
        $betsy_api = "http://yourbetsy.com/rest/V1/mjsi-distribution/post/getProducts";
        $json_data = $this->cleanJson(file_get_contents($betsy_api));
        $json_data = json_decode($json_data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $column = ['entity_id','price','msrp','msrp2',
        'special_price','special_price_from_date','special_price_to_date'];
        foreach (range('A', 'G') as $ltr) {
            foreach ($column as $key => $col) {
                $sheet->setCellValue($ltr.'1', $col);
            }
        }

        $mismatch_price_count = 0;

        $row_count = 2;
        foreach ($json_data as $key => $value) {
            $consolebetsy_item = Items::where('sku',$value->sku)->first();
            if ($consolebetsy_item) {
                if ($consolebetsy_item->price !== $value->price) {

                    $sheet->setCellValue('A'.$row_count, $value->entity_id);
                    $sheet->setCellValue('B'.$row_count, $consolebetsy_item->price);
                    $sheet->setCellValue('C'.$row_count, 0);
                    $sheet->setCellValue('D'.$row_count, 0);
                    $sheet->setCellValue('E'.$row_count, $consolebetsy_item->special_price);
                    $sheet->setCellValue('F'.$row_count, '');
                    $sheet->setCellValue('G'.$row_count, '');
        
                    $row_count++;
                    $mismatch_price_count++;
                }
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
        $writer->save('./storage/app/public/export/price.csv');

        echo "total mismatch item price: ".$mismatch_price_count;     
    }

    protected function cleanJson($json) {
        $json = str_replace('"[', '[', $json);
        $json = str_replace(']"', ']', $json);
        $json = html_entity_decode($json);
        $json = stripslashes($json);
        return $json;
    }
}
