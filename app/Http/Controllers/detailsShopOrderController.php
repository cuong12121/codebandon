<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use Validator;

use DB;

use App\Imports\detailsShopOrderImport;

use App\detailsShopOrder;

use Carbon\Carbon;

class detailsShopOrderController extends Controller
{
    
    public function showIndexContainerCost(){
        
        return view('DetailsShopOrder.import-cost');
    }
    
    public function showIndexDetail(){
        
        return view('DetailsShopOrder.import-details');
    }
    public function index()
    {
        
        return view('DetailsShopOrder.import');
    }

    public function convertDateExcel($number)
    {

        $startdate = strtotime('1900/1/1');


        $number = ($number-2)*86400;

        $date1  = $number + $startdate;

        $time = date('Y/m/d', $date1);

        return $time;

    
    }
    
    public function insertContainerCost(Request $request)
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
            
           
            foreach ($importedData[0] as $key =>$row) {
                
                if ($row[0] !== null && $key >2) {
                    
                    dd($row[0]);
                }    
                
            }    
        }   
    }
    
    public function insertBoothApi(){
        
       
        
        $no_small_price = [];
        
        $time = DB::table('details_shop_order')->select('date_document','id')->orderBy('id', 'desc')->first();
        
        // lấy thông tin  shop từ bảng chi tiết để show ra
        
        $data_shop = DB::table('details_shop_order')->select('client_id')->where('date_document', $time->date_document)->distinct()->get();
        
        
        foreach($data_shop as $values){
            
             $totalrevenue  = $total_return_value =  $totalnet_revenue = $add_small_price =  $totalcost = $totalreturn_cost = $totalpackaging_cost = $totalshipping_cost = $totalads_cost = $totalother_cost = $totalgross_profit = $total_price_sale = $total_price_return = $total_cpdg_sauco = 0;
             
            $code = $values->client_id;
            
            // lấy data bảng chi tiết vừa đẩy vào
            
            
            $data_shops = DB::table('details_shop_order')->where('client_id', $code)->where('date_document', $time->date_document)->get();
            
            if(!empty($data_shops)){
                        
                foreach($data_shops as  $value){
                    
                    $check_small_price = DB::table('small_price')->select('price')->where('code', $value->sku)->first();
                    
                    if(!empty($check_small_price)){
                        
                        $small_price =  $check_small_price->price;
                        
                        // kiểm tra nếu là đơn hàng fake thì không tính vào doanh số bán nữa
                        
                        if(strpos($value->broad_interpretation, 'FAKE')){
                            
                            $add_small_prices = 0;
                            
                        }
                        else{
                            $add_small_prices = $small_price*intval($value->quantity);
                        }
                        
                        
                                
                        $add_small_price += $add_small_prices;
                        
                        $total_return_value += ($small_price*intval($value->return_revenue));
                        
                        $total_price_sale += (intval($value->price)*intval($value->quantity));
                        
                        $total_price_return += (intval($value->price)*intval($value->return_revenue));
                        
                        $total_cpdg_sauco += intval($value->cpdg_sauco);
                        
                        $DSB = intval($add_small_price);
                
                        $DSTL = intval($total_return_value);
                        
                        $GVHB = intval($total_price_sale);
                        
                        $GVHTL = intval($total_price_return);
                        
                        $LNT = intval($add_small_price) - intval($total_return_value);
                        
                        $LNTT = $LNT -($GVHB -$GVHTL) - $total_cpdg_sauco;
                        
                        
                    }
                    else{
                        array_push($no_small_price, $value->sku);
                    }
                    
                    
                
                }
                
                $data_info_shop = DB::table('details_shop_order')->select('name_client', 'address')->where('client_id', $value->client_id)->first(); 
                
                $insert = ['name'=>$data_info_shop->name_client, 'code'=>$values->client_id, 'address'=> $data_info_shop->address, 'manager_staff'=>'', 'revenue'=>$DSB, 'return_revenue'=>$DSTL, 'net_revenue'=>$LNT, 'cost'=>$GVHB, 'return_cost'=>$GVHTL, 'packaging_cost'=>$total_cpdg_sauco, 'shipping_cost'=>0, 'ads_cost'=>0, 'other_cost'=>0, 'gross_profit'=>$LNTT, 'updated_at'=>$time->date_document, 'created_at'=>$time->date_document];
                        DB::table('booth_api')->insert($insert);
                
            }    
            
            
        }  
        
        return $no_small_price;
       
        
    }
    
   public function insertData($table, $data, $file){
       
        $validator = Validator::make($data, [
            'file' => 'required|max:10000|mimes:xls,xlsx',
           
        ]);
        
        if ($validator->fails()) {

            return back()->withErrors($validator)->withInput();
        }
        else{
           

            $importedData = Excel::toArray([], $file);
            
           
            foreach ($importedData[0] as $key =>$row) {
                if ($row[0] !== null && $key >2) {
                    
                    $data = [
                        'date_document'=> $this->convertDateExcel($row[0]),
                        'number_document'=> $row[1],
                        'invoice_number'=> $row[2],
                        'broad_interpretation'=> $row[3],
                        'explain'=> $row[4],
                        'client_id'=> $row[5],
                        'name_client'=> $row[6],
                        'address'=> $row[7],
                        'sku'=> $row[8],
                        'product_name'=> $row[9],
                        'unit'=> $row[10]??'',
                        'quantity'=> $row[11]??'',
                        'price'=> $row[12],
                        'tk_no'=> $row[13],
                        'tk_co'=> $row[14],
                        'revenue'=> $row[15],
                        'return_revenue'=> $row[16],
                        'return_value'=> $row[17],
                        'number_of_entries'=> $row[18],
                        'tk_gia_von'=> $row[19],
                        'tk_kho'=> $row[20],
                        'ma_kho'=> $row[21],
                        'ten_kho'=> $row[22],
                        'lazada_code'=> $row[23],
                        'cpdg'=> $row[24],
                        'cpdg_sauco'=> $row[25],
                    ];
                    
                    DB::table($table)->insert($data);
                }
            } 
        }
       
   }
    
    public function storeDetailsNorth(Request $request)
    {
        $data = $request->all();
        
        $file = $request->file('file');
        
        $table ='details_shop_order_north';
        
        $this->insertData($table, $data, $file);    
        

    }
    
    public function storeDetailsSouth(Request $request)
    {
        $data = $request->all();
        
        $file = $request->file('file');
       
        $table ='details_shop_order_south';
        
        $this->insertData($table, $data, $file);    
        

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
            
           
            foreach ($importedData[0] as $key =>$row) {
                if ($row[0] !== null && $key >2) {
                    
                    detailsShopOrder::create([
                        'date_document'=> $this->convertDateExcel($row[0]),
                        'number_document'=> $row[1],
                        'invoice_number'=> $row[2],
                        'broad_interpretation'=> $row[3],
                        'explain'=> $row[4],
                        'client_id'=> $row[5],
                        'name_client'=> $row[6],
                        'address'=> $row[7],
                        'sku'=> $row[8],
                        'product_name'=> $row[9],
                        'unit'=> $row[10]??'',
                        'quantity'=> $row[11]??'',
                        'price'=> $row[12],
                        'tk_no'=> $row[13],
                        'tk_co'=> $row[14],
                        'revenue'=> $row[15],
                        'return_revenue'=> $row[16],
                        'return_value'=> $row[17],
                        'number_of_entries'=> $row[18],
                        'tk_gia_von'=> $row[19],
                        'tk_kho'=> $row[20],
                        'ma_kho'=> $row[21],
                        'ten_kho'=> $row[22],
                        'lazada_code'=> $row[23],
                        'cpdg'=> $row[24],
                        'cpdg_sauco'=> $row[25],
                    ]);
                }
            } 
            
            $no_small_price = $this->insertBoothApi();
            
            if(count($no_small_price)>0){
                
                print_r($no_small_price);
                
            }
            else{
                echo "thành công";
            }
            
        
        }
       

    }
}
