<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use Validator;

use App\Imports\QuantityImport;

class importQuantityController extends Controller
{
    public function index()
    {
        return view('import');
    }

    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|max:1000|mimes:xls,xlsx',
           
        ]);
        
        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        }
        else{
            $data = Excel::import(new QuantityImport,request()->file('file'));
             
            return back()->with('success', 'thành công');
        }
 
        
    }
}
