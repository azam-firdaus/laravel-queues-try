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
            $temp_header = ['', '', '', '', '', '', '',''];
            $batch  = Bus::batch([])->dispatch();
            
            foreach ($chunks as $key => $chunk) {
                $data = array_map('str_getcsv', $chunk);
                $totaltt = array();
                if($key == 0){
                    $header = $data[0];
                    unset($data[0]);
                }
                else{
                    foreach($header as $key => $val){
                        switch ($val) {
                            case 'UNIQUE_KEY':
                                $temp_header[0] = $key;
                                break;
                            case 'PRODUCT_TITLE':
                                $temp_header[1] = $key;
                                break;
                            case 'PRODUCT_DESCRIPTION':
                                $temp_header[2] = $key;
                                break;
                            case 'STYLE#':
                                $temp_header[3] = $key;
                                break;
                            case 'SANMAR_MAINFRAME_COLOR':
                                $temp_header[4] = $key;
                                break;
                            case 'SIZE':
                                $temp_header[5] = $key;
                                break;
                            case 'COLOR_NAME':
                                $temp_header[6] = $key;
                                break;
                            case 'PIECE_PRICE':
                                $temp_header[7] = $key;
                            break;
                            
                            default:
                              
                          }
                    }
                    for($i = 0; $i < count($data); $i++){
                        $testest = array();
                        $testest[] = $data[$i][$temp_header[0]];
                        $testest[] = $data[$i][$temp_header[1]];
                        $testest[] = $data[$i][$temp_header[2]];
                        $testest[] = $data[$i][$temp_header[3]];
                        $testest[] = $data[$i][$temp_header[4]];
                        $testest[] = $data[$i][$temp_header[5]];
                        $testest[] = $data[$i][$temp_header[6]];
                        $testest[] = $data[$i][$temp_header[7]];

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