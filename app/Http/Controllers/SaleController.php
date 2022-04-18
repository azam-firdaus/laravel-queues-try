<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Jobs\ProcessSaleCsv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SaleController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function upload_csv_records(Request $request)
    {
        if( $request->has('csv') ) {

            $csv    = file($request->csv);
            $chunks = array_chunk($csv,1000);
            $header = [];
            $header2 = ['key', 'title', 'description', 'style', 'mainframe', 'size', 'color','price'];
            $batch  = Bus::batch([])->dispatch();
            
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                $totaltt = array();
                if($key == 0){
                    $header = $data[0];
                    unset($data[0]);
                }
                else{

                    for($i = 0; $i < count($data); $i++){
                        $testest = array();
                        $testest[] = $data[$i][0];
                        $testest[] = $data[$i][1];
                        $testest[] = $data[$i][2];
                        $testest[] = $data[$i][3];
                        $testest[] = $data[$i][28];
                        $testest[] = $data[$i][18];
                        $testest[] = $data[$i][14];
                        $testest[] = $data[$i][21];

                        $totaltt[] = $testest;
                    }
                }
                
                $batch->add(new ProcessSaleCsv($totaltt, $header2));
                // $batch->add(new ProcessSaleCsv($data, $header));
            }
            return $batch;
        }
        return "please upload csv file";
    }
}