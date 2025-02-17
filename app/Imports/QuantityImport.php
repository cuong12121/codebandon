<?php

namespace App\Imports;

use App\qualtity;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use DB;

class QuantityImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        DB::table('check_qualtity')->insert(['model'=>$row[0], 'qualtity'=>$row[1], 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]);
        DB::table('check_qualtity')->insert(['model'=>$row[2], 'qualtity'=>$row[3],'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]);
        return new qualtity([
            'model'=>$row[4],
            'qualtity'=> $row[5],
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
            
        ]);
    }
}
