<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use Validator;

use DB;

class smallPriceController extends Controller
{
    public function index()
    {
        
        return view('SmallPrice.import');
    }
    
    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|max:10000|mimes:xls,xlsx',
           
        ]);
        
        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        }
        else{
            $file = $request->file('file');

            $importedData = Excel::toArray([], $file);
            
            DB::table('small_price')->delete();


            foreach ($importedData[0] as $key =>$row) {
                if ($row[0] !== null && $key !=0) {
                    
                    DB::table('small_price')->insert([
                        'name'=> $row[2],
                        'code'=> $row[1],
                        'price'=> $row[3],
                        
                    ]);
                }
            }    
             
          
        }
        echo "thành công";
 
        
    }
}
