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
            
            
            $cococ = 0;
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                $totaltt = array();
                if($key == 0){
                    $header = $data[0];
                    unset($data[0]);
                }
                else{
                    $testest = array();
                    $testest[] = $chunk[0];
                    $testest[] = $chunk[1];
                    $testest[] = $chunk[2];
                    $testest[] = $chunk[3];
                    $testest[] = $chunk[28];
                    $testest[] = $chunk[18];
                    $testest[] = $chunk[14];
                    $testest[] = $chunk[21];

                    $totaltt[] = $testest;
                }

                for($i = 1; $i < count($data); $i++){
                     
                }
                $batch->add(new ProcessSaleCsv($totaltt, $header2));
                // $batch->add(new ProcessSaleCsv($data, $header));
                $cococ++;
                // dd($data);
            }
            dd('STOPOPOPOPO');
            return $batch;
        }
        return "please upload csv file";
    }
}