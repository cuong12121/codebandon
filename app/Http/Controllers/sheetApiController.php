<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use DB;



class sheetApiController extends Controller
{
    
    public function showComboCodePD()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1R8wqOZH6s2gR5_iLbF6EczJEs7tYkcEW7nGv3Rp7yws'; 
        $range = 'Order!A:E'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();
        
       
        for ($i=1; $i < count($values); $i++) { 
            
            $code_combo = !empty($values[$i][4])?$values[$i][4]:'';
            
            $update = DB::table('fs_products')->where('code', $values[$i][0])->update(['code_combo'=>$code_combo]);
            
            echo $values[$i][0].'<br>';
            
        }
       echo 'thành công';
    
    }    

    public function show_data_sale()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1pogJMPoPVF6NsWyJEGmRsc1XcKymYcYQW3Y5sNzYYxs'; 


        $range = 'sale1!A:E'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $k = 0;

        $data = [];
       
        for ($i=1; $i < count($values); $i++) { 

            if(!empty($values[$i][0])){

                $k++;

                $data[$k]['model'] = $values[$i][0];

                $data[$k]['sale'] = $values[$i][3];

              
            }
        }

        $redis = new \Redis();

            // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);

         
        $redis->set("sale_model", json_encode($data));
         
        echo"thành công";
  
    } 

    public function testApi()
    {

        $string_hex = $_GET['string'];

        $product_sku = trim($_GET['product_sku']);

        $price       = $_GET['price'];

        if($string_hex ==='AtBSvrztfw5hXwxZeIf0fWf3GtuILFeCOBsRtnah'){

            $client = new \Google\Client();
            $client->setApplicationName('Google Sheets API PHP');
            $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
            $client->setAuthConfig(storage_path('app/key.json'));
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $service = new \Google\Service\Sheets($client);
            $spreadsheetId = '1V-7nV2Lzbl4-kZHFKjTqSc9u70M_HGKvGyCa0ivXL9Q'; 
            $range = 'Trang tính1!A:F'; 

            //phần nhận dữ liệu 

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);

            $values = $response->getValues();
            
           
            $ar_sku = [];
         
            for ($i=0; $i < count($values); $i++) { 
                
                if(!empty($values[$i][0])){
                    
                    $ar_sku[$i] = trim($values[$i][0]);
                    
                }
                else{
                    $ar_sku[$i] = 'space';
                    
                }
            }
            
    
            if(count($ar_sku)>0){
                
                $key_search = array_search($product_sku, $ar_sku);
                    
                if($key_search >-1){

                    $values[$key_search][5] = $price.' VND';
                
                    // phần update lại docsheet
                    $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);
                    $params = ['valueInputOption' => 'RAW'];
                    $result = $service->spreadsheets_values->update($spreadsheetId, $range,$body, $params); 
                }

            }
            
        }
        else{
            return abort('404');
        }

    }

    public function getPSK()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1-8OLPIqhHt233x7UUxO2iiNMlQ0l6ws5Aow7H6_GCqg'; 


        $range = 'Trang tính1!I:N'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $ar_sku = [];

        // lay toan bo model trong sheet day vao mang
         
        for ($i=0; $i < count($values); $i++) { 
            
            if(!empty($values[$i][0])){
                
                $ar_sku[$i] = trim($values[$i][0]);
                
            }
            else{
                $ar_sku[$i] = 'space';
                
            }
        }

        return $ar_sku;
    }
    
    public function updateShop()
    {
        $data = DB::table('booth_api')->get();
        
        $ar_data = [];
        
       
        
        foreach($data as  $value){
            
            $ar_data['name'] = $value->name;
            $ar_data['code'] = $value->code;
            $ar_data['address'] = $value->address;
            $ar_data['manager_staff'] = $value->manager_staff;
            $insert = DB::table('info_shop')->insert($ar_data);
            
        }
        
        echo 'thành công';
        
    }


    public function test()
    {
        $ar_sku = $this->getPSK();
        
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1UqytdpDe7CYM3OOXfLiyes2YUBzub87AcIXiYF4PZj0'; 
        
        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
               
            )
        ));

        // Send the request
         $range = 'BCLN GIAN!A:O'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();


        $data = [];

        $stringDate = "28-12-2023"; // Your string representing the date in a different format

        $date = Carbon::parse($stringDate)->format('Y/m/d');


        // Convert the string to a Carbon instance with a specific format

    
        for($i=3; $i<count($values); $i++){

            $data['code'] = $values[$i][1];
            $data['name'] = $values[$i][2];
            
            $data['address'] =  $values[$i][3]??'0';
            $data['manager_staff'] = $values[$i][4];

            $data['revenue'] =  !empty($values[$i][5])?str_replace('.', '',$values[$i][5]):0;
            $data['return_revenue'] = !empty($values[$i][6])?str_replace('.', '',$values[$i][6]):0;
            $data['net_revenue'] = !empty($values[$i][7])?str_replace('.', '',$values[$i][7]):0;
            $data['cost'] = !empty($values[$i][8])?str_replace('.', '',$values[$i][8]):0;
            $data['return_cost'] = !empty($values[$i][9])?str_replace('.', '',$values[$i][9]):0;
            $data['packaging_cost'] = !empty($values[$i][10])?str_replace('.', '',$values[$i][10]):0;
            $data['shipping_cost'] = !empty($values[$i][11])?str_replace('.', '',$values[$i][11]):0;
            $data['ads_cost'] = !empty($values[$i][12])?str_replace('.', '',$values[$i][12]):0;
            $data['other_cost'] = !empty($values[$i][13])?str_replace('.', '',$values[$i][13]):0;
            $data['gross_profit'] = !empty($values[$i][14])?str_replace('.', '',$values[$i][14]):0;
            $data['created_at'] = $date;
            $data['updated_at'] = $date;
            DB::table('booth_api')->insert($data);

        }
        echo "thành công";

    }
    
    public function getPDTran()
    {
        $ar_sku = $this->getPSK();
        
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1ThMhTunmDeE07y9BA7fwNwBaU8JV0Jaaw2Jo9z6CzXo'; 
        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
               
            )
        ));

        // Send the request
        $range = 'TIẾN ĐỘ HÀNG VỀ!A:J'; 

        $values = $service->spreadsheets_values->get($spreadsheetId, $range);

        $data = [];


        for($i=1; $i<count($values); $i++){

            if(!empty($values[$i][0]) && $values[$i][0] != '#REF!'){

                $data7 = $values[$i][7]??'';
                if(!empty($values[$i][7] ) && $values[$i][7]==='18/07/1900'){
                    $data7 = '18/07/2023';
                }
                $data['internal_code'] = $values[$i][0];
                $data['order_date'] = !empty($values[$i][1])?Carbon::parse(trim(str_replace('/', '-', $values[$i][1])))->format('Y/m/d'):NULL;
                $data['sku'] = $values[$i][2]??'';
                $data['fast_code'] = !empty($values[$i][3])?trim($values[$i][3]):'';
                $data['name'] = !empty($values[$i][4])?trim($values[$i][4]):'';
                $data['quantity'] = !empty($values[$i][5])?trim($values[$i][5]):0;
               
                $data['delivery_date'] = !empty($values[$i][6])?Carbon::parse(trim(str_replace('/', '-', $values[$i][6])))->format('Y/m/d'):NULL;
                $data['import_date'] =  !empty($data7)?Carbon::parse(trim(str_replace('/', '-', $data7)))->format('Y/m/d'):NULL;
                $data['status'] = !empty($values[$i][8])?1:0;
          
                $data['done_date'] = !empty($values[$i][8])?trim(str_replace('DONE', '', $values[$i][8])):'';

                $data['note'] = $values[$i][9]??'';

                $data['created_at'] = Carbon::now(); 

               
                
                try{
                    $insert = DB::table('tracking_order')->insert($data);
                }
                
                catch (\Exception $e) {
                   
                   var_dump($values[$i][0]);
                   
                   die();
                }
                
             
            }

            
        } 
        echo "thành công";

    }

    public function updatePriceInGoogleSheet()
    {
        $data = $request->data;

        $hash = 'Vi6mQUxDl2hRczjclpYybfmnJNxYRKFwoNW6Z9ws0ITasl7x03VmIOvGwWjBTSl9goAgiezqOJSNAo7mxzdo3rpn0m89ZMiE2T87';

        if($data['secret']===$hash){
            $client = new \Google\Client();
            $client->setApplicationName('Google Sheets API PHP');
            $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
            $client->setAuthConfig(storage_path('app/key.json'));
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $service = new \Google\Service\Sheets($client);
            $spreadsheetId = '1V-7nV2Lzbl4-kZHFKjTqSc9u70M_HGKvGyCa0ivXL9Q'; 
            $range = 'Trang tính1!A1:F100'; 

            //phần nhận dữ liệu 

            $response = $service->spreadsheets_values->get($spreadsheetId, $range);

            $values = $response->getValues();

            // dd($values[1][0]);

            for ($i=0; $i < count($values); $i++) { 

                if(array_search('RB27N4010S8/SV', $values[$i])===0){

                    dd($i);

                }
                
            }
        }
    }

    public function getPrice()
    {
        $id_page = $_GET['id_page']??0;

        $page = $_GET['range']??'LG';

        $pages = $_GET['range']??'LG';

        $id_page = $_GET['id_page']??1;

        $define_page    = ['LG', 'Samsung', 'Sony', 'TCL', 'Philips', 'Sharp'];

        $define_product = ['tivi', 'tivi', 'Máy giặt', 'Tủ lạnh'];

        $define[1] = ['LG'=>8, 'Samsung'=>7, 'Sony'=>5, 'TCL'=>7, 'Philips'=>4, 'Sharp'=> 7];

        $define[2] = ['LG'=>8, 'Samsung'=>8, 'Panasonic'=>8, 'Sharp'=>8, 'Electrolux'=>8];

        $define[3] = ['LG'=>8, 'Samsung'=>8, 'Panasonic'=>8, 'Sharp'=>8,  'Mitsubishi'=>8,'Hitachi'=>8, 'Funiki'=>8];

        $define[4] = ['Sanaky'=>8, 'kangaroo'=>8, 'Hòa Phát'=>8];

        $selected  = $define[$id_page][$page];

        $ar_id = ['1BAk7_PBEN-uuOMFtAYxU4mZkzRxIPN6eeWmwz70Ds04', '1BAk7_PBEN-uuOMFtAYxU4mZkzRxIPN6eeWmwz70Ds04', '1wznHZD6U42AiuM-S9jp-lvl5s8Fif1B__sJG5Fc6ewY','1BJDMWe1M3C28d90XLFMBygFb2pzufpA3Lt8vXeuso0I','1tG47I_lYiMG-D6dCltD0jMxmCVNVzv8Ga9C0Sjmm5bA'];

        $id_page = $ar_id[$id_page];


        $values = $this->taskGetPrice($id_page, $pages);

        return view('table',compact('values', 'define', 'page', 'id_page', 'selected', 'define_page'));

    }

    public function runPriceToDrive()
    {
        $number =2;

        // $this->getdataQuantity($number);

        $this->getdataQuantityMN($number);
        
        echo "thanh cong";

    }


    public function getdataQuantity()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $id_page = '1dWx77w3OKyisRa2QUNfVBX2145bgR58vTX6OHUgxaj8'; 
        $spreadsheetId = $id_page; 
        $range = 'NXTPL!A:AC'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

       

       
        $number = 1;

        for($i=2; $i<count($values); $i++){
            $insert['model'] = $values[$i][0];

            $insert['quantity'] =   !empty($values[$i][28])?str_replace(',', '.',$values[$i][28]):0;

            $insert['number']  = $number;

            // 1 la ha noi

            $insert['address'] = 1;
            $insert['created_at'] = Carbon::now();
            $insert['updated_at'] = Carbon::now();


            DB::table('fs_quantity')->insert($insert);
        }


        echo "thanh cong";
       
    }

    public function getdataQuantityMN($number)
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $id_page = '1EojJFXhjcqAuhPXh8O7OgdIG0ut99fBIArSMy8obIHc'; 
        $spreadsheetId = $id_page; 
        $range = 'TONG HOP 3 KHO!A:H'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $number = 0;


        foreach($values as $key=> $val){

            if($key>2){

                $insert['model'] = $val[1];

                $insert['quantity'] =   !empty($val[7])?str_replace(',', '.', $val[7]):0;

                $insert['number']  = $number;

                // 1 la ha noi

                $insert['address'] = 2;

                DB::table('fs_quantity')->insert($insert);
            }    
        }

        echo "thanh cong";
       
    }


    public function showAPi(Request $request)
    {

        $token = $request->header('token');

        if($token != 'yjzcbvngslvejdqxcadkzskokspfnjge'){

            return response()->json(['message'=> 'token not found'], 401);
        }
        else{

            $address = $_GET['address'];

            $data = DB::table('fs_quantity')->where('address', $address)->get();

            $datas = [];

            foreach($data as $key=>$val){

                $datas[$key]['model'] = $val->model;
                $datas[$key]['quantity'] = $val->quantity;
                $datas[$key]['address'] = $val->address;
                $datas[$key]['product_id'] = $val->product_id;


            }

            echo json_encode($datas);


        }
       
    }


    public function runQuantity()
    {
        
        $data = DB::table('fs_quantity')->where('address', 1)->get();

        foreach($data as $value){

            //lấy thông tin sản phẩm để update lại số lượng hàng hóa

            $update = DB::table('fs_warehouses_products')->where('product_id', $value->product_id)->where('warehouses_id', 1)->update(['amount'=>$value->quantity]);

        }

        $datas = DB::table('fs_quantity')->where('address', 2)->get();

        foreach($datas as $value){

            //lấy thông tin sản phẩm để update lại số lượng hàng hóa

            $update = DB::table('fs_warehouses_products')->where('product_id', $value->product_id)->where('warehouses_id', 2)->update(['amount'=>$value->quantity]);

        }
        echo "thành công";

    }

    public function convertIDtoModel()
    {
        $data = DB::table('fs_quantity')->select('model','product_id', 'id')->get();

        foreach ($data as $key => $value) {

            // tìm id product trong bảng sản phẩm

            $vals = DB::table('fs_products')->select('id')->where('code', trim($value->model))->first();

            if(!empty($vals->id)){
                 $update = DB::table('fs_quantity')->where('id', $value->id)->update(['product_id'=>$vals->id]);
            }

        }
        echo "thành công";

    }


    public function taskGetPrice($id_page, $pages)
    {

        $page = $_GET['page']??'';

        $token = $_GET['token']??'';



        $ranges = $pages??'LG';


        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = $id_page; 
        $range = $ranges.'!A:I'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        return $response->getValues();
    }

    public function updateDataSheetPrice(Request $request)
    {

        $postData = json_encode($request->data);

        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'PUT',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
                'content' => $postData
            )
        ));

        // Send the request
        $response = file_get_contents('http://localhost/pj5/api/api-update-product-all', FALSE, $context);

        // Decode the response
     
        $string = str_replace('\\', '', $response);

        $string = rtrim($string, ',');

        // $string = "[" . trim($string) . "]";

        $info_data = $string;

        return $info_data;

    }


    public function showDataUpdate()
    {

        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1V-7nV2Lzbl4-kZHFKjTqSc9u70M_HGKvGyCa0ivXL9Q'; 
        $range = 'Trang tính1!A:F'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $ar_sku = [];

        // lay toan bo model trong sheet day vao mang
         
        for ($i=0; $i < count($values); $i++) { 
            
            if(!empty($values[$i][0])){
                
                $ar_sku[$i] = trim($values[$i][0]);
                
            }
            else{
                $ar_sku[$i] = 'space';
                
            }
        }

        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
               
            )
        ));

        // Send the request
        $response = file_get_contents('http://localhost/pj5/api/show-list-product-update', FALSE, $context);

        $response = json_decode($response, true);

        if(!empty($response) && count($response)>0){

            foreach ($response as $value) {

                $key_search = array_search(trim($value['product']), $ar_sku);
                    
                if($key_search >-1){

                    $values[$key_search][5] = trim($value['price']).' VND';
                }
                
            }

            // // phần update lại docsheet
            $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);
            $params = ['valueInputOption' => 'RAW'];
            $result = $service->spreadsheets_values->update($spreadsheetId, $range,$body, $params); 

        }


    }


    public function viewQualtity()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1tQVIiVulCSOQAJobQMPuq1pteX1XKBp1giTkEDXK6_0'; 
        $range = 'tonkho!A:F'; 
        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $now = Carbon::now();

        // dd($values);

        foreach ($values as $key => $val) {

            // nếu tồn tại model thì lấy dữ liêu đẩy vào db
            if(count($val)>3){
                $new_ar_chunk = array_chunk($val, 2);
                foreach($new_ar_chunk as $vals){

                    DB::table('check_qualtity')->insert(['model'=>$vals[0], 'qualtity'=>trim($vals[1]), 'created_at'=>$now, 'updated_at'=>$now]);
                }

            }
            

        }
        echo "thành công";
    }


    public function updateDealPriceForSheet()
    {
        
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1V-7nV2Lzbl4-kZHFKjTqSc9u70M_HGKvGyCa0ivXL9Q'; 
        $range = 'Trang tính1!A:F'; 

        //phần nhận dữ liệu 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);

        $values = $response->getValues();

        $ar_sku = [];

        // lay toan bo model trong sheet day vao mang
         
        for ($i=0; $i < count($values); $i++) { 
            
            if(!empty($values[$i][0])){
                
                $ar_sku[$i] = trim($values[$i][0]);
                
            }
            else{
                $ar_sku[$i] = 'space';
                
            }
        }

        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
               
            )
        ));

        // Send the request
        $response = file_get_contents('https://dienmaynguoiviet.vn/api/show-list-deal-update-price', FALSE, $context);
        
        $response = json_decode($response, true);

        if(!empty($response) && count($response)>0){

            foreach ($response as $value) {

                $key_search = array_search(trim($value['product']), $ar_sku);
                    
                if($key_search >-1){

                    $values[$key_search][5] = trim($value['price']).' VND';
                }
                
            }

            // // phần update lại docsheet
            $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);
            $params = ['valueInputOption' => 'RAW'];
            $result = $service->spreadsheets_values->update($spreadsheetId, $range,$body, $params); 

        }
         echo 'thành công lúc '. Carbon::now()->format('d/m/Y, H:i:s');


    }

    public function checkProduct(Request $request)
    {
        $postData = json_encode($request->data);

        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'PUT',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: eecc19a1cabb51a5080f6f56399f7e82",
                'content' => $postData
            )
        ));

        // Send the request
        $response = file_get_contents('http://localhost/pj5/api/api-check-product', FALSE, $context);

        return view('ajax-table', compact('response'));


    }

    public function showQualtity()
    {
        $data = DB::table('check_qualtity')->select('model', 'qualtity')->whereDate('created_at', '=', Carbon::today()->toDateString())->get()->toArray();

        $data = json_encode($data);

        return $data;

    }

    //phần check giá cho sheet 

    public function getModelsToSheet()
    {
        $client = new \Google\Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig(storage_path('app/key.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1HlY5MwiIrZ-M_RZQIA_hqy_gkwHRTISuZQbvH3qKgmM';
        $range_model = 'LG!A:A';
        $value_model = $service->spreadsheets_values->get($spreadsheetId, $range_model);
       
        foreach($value_model as $key =>$model){

            sleep(0.5);

            if($key>=2){

                if(!empty($model[0])){

                    // phần crawl giá sản phẩm

                    $key_sheet = $key+1;

                    // crawl sản phẩm từ điện máy xanh. Kiểm tra liên tục khi bên đấy update code

                    $data_price = $this->crawl_web(trim($model[0]));
                    
                    $data_dmx = $this->CrawlDienMayxanh($model[0]);

                    array_unshift($data_price, $data_dmx);

                    $values[0] = $data_price;

                    
                    $range_update = 'LG!M'.$key_sheet.':P'.$key_sheet;

                    $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);

                    $params = ['valueInputOption' => 'RAW'];

                    $result = $service->spreadsheets_values->update($spreadsheetId, $range_update,$body, $params);
                }

            
            }

        }

        echo"thành công!";

    }


    public function CrawlDienMayxanh($model='')
    {


        $link = 'https://www.dienmayxanh.com';

        $ch = curl_init($link);

        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36';

        curl_setopt($ch, CURLOPT_URL, 'https://www.dienmayxanh.com/search?key='.$model);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch,CURLOPT_USERAGENT, $user_agent);

        $content = curl_exec($ch);

        curl_close($ch);

        $dom = str_get_html($content);

        $price = '';

        if($dom != false){

            $price = $dom->find('.filter-catesearch .price', 0);

            $price = str_replace('&#x20AB;', '', strip_tags($price));
        }
    
        return  $price;
    
    }


    public function crawl_web($model)
    {
       
        $ar = [
            ['name_url'=>'https://dienmayabc.com',  'site'=>'https://dienmayabc.com/tim?q='.$model, 'name'=>'.name a', 'price'=>'.price', 'remove'=>'.percent'],
            ['name_url'=>'https://dienmaytinphat.com', 'site'=>'https://dienmaytinphat.com/?s='.$model, 'name'=>'.info h3 a', 'price'=>'.info .price'],
            ['name_url'=>'https://manhnguyen.com.vn', 'site'=>'https://manhnguyen.com.vn/tim-kiem?q='.$model, 'name'=>'.MLNname h2', 'price'=>'.product_item strong', 'remove'=>'.old_price'],
            
        ];
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),

        );  

       
        $info  = [];
        
    
        foreach ($ar as $key=>  $value) {
           
            if($key==2){
                $html = file_get_html(trim($value['site']),  false, stream_context_create($arrContextOptions));
            }
            else{
                $html = file_get_html(trim($value['site']));
            }
            $price = strip_tags($html->find($value['price'], 0));
            $name  = strip_tags($html->find($value['name'], 0));

           
            if(!empty($value['remove'])){

                $remove = strip_tags($html->find($value['remove'], 0));  
                $price  = str_replace($remove, '', $price);
            }



            // muốn kiểm tra kết quả từ website thì bật lại

            // $info[$key]['name_url'] = $value['name_url'];
            // $info[$key]['name'] = $name;
            // $info[$key]['price'] = trim(str_replace(['đ', '&#8363', ';'], '', $price));

            $result[]  = trim(str_replace(['đ', '&#8363', ';'], '', $price));
        }

        return $result;
        
    }

}
