<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use phpseclib\Net\SFTP;
use Storage;
use Auth;

class ResellerShipmentRateController extends Controller
{

    public function index() {
        return view('reseller.reseller-shipmentrate');
    }

    public function export() {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=tablerate.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $csv = fopen("php://output", "w");
        fputcsv($csv, [
            'Country','Region','ZIP Code','SKU','Shipping Price'
        ]);

        $region_list = ph_regions();
        foreach ($region_list as $value) {
            fputcsv($csv, ['PH',$value,'*','','']);
        }

        fclose($csv);
    }

    public function import() {
        $status = 'error';
        $file = request()->file("csv");
        if (!empty($file)) {
            $tablerateDIR = "public/tablerate";
            $check_tablerateDIR_exist = Storage::disk('local')->exists($tablerateDIR);
            if (!$check_tablerateDIR_exist) {
                Storage::makeDirectory($tablerateDIR);
            }

            $temp_filename = strtotime(date("Y-m-d H:i:s")).'-'.Auth::user()->id.'.'.$file->getClientOriginalExtension();
            Storage::put($tablerateDIR."/".$temp_filename,file_get_contents($file));
            $status = 'success';
        }
        return response()->json(['status' => $status], 200);
    }

    protected function fopen($file, $mode) {
        if(!file_exists(public_path('storage/tablerate/'.$file))) {
            Storage::put('public/tablerate/'.$file, '');
        }
        return fopen(public_path('storage/tablerate/'.$file), $mode);
    }
}