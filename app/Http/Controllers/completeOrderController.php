<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Session;

class completeOrderController extends Controller
{
    public function viewOrder(Request $request)
    {
        // $redis = new \Redis();

        // Thiết lập kết nối
        // $redis->connect('127.0.0.1', 6379);

        // $info_datas = [];
        // $keyExists = $redis->exists('api_complete_box');

        // if ($keyExists) {
        //     $info_datas = collect(json_decode($redis->get("api_complete_box")));
        //     $info_datas = $info_datas->where('is_package', '1');

        // }   
        // // dd(collect($info_data));

        // if(!empty($info_datas)){
        //     $page = request()->get('page', 1);  // or use any method to get the current page
        //     $perPage = 10;  // Number of items per page

        //     // Slice the collection to get the items for the current page
        //     $items = $info_datas->slice(($page - 1) * $perPage, $perPage)->values();

        //     // Create the paginator
        //     $info_data = new LengthAwarePaginator($items, $info_datas->count(), $perPage, $page, [
        //         'path' => LengthAwarePaginator::resolveCurrentPath(), // Ensure correct pagination links
        //     ]);
        //     dd($info_data);

        //     return view('admin.complete_order', compact('info_data'));
        // }

        

        // Use the paginator in a view or controller
        // dd($paginator);

        $check_active = $request->check_active;

        $sesion = Session()->get('admin');

        if($sesion[0] ==='adminmn'){
            $kho =2;
        }
        else{
            $kho =1;
        }
       

        if($check_active==="0"){
            
            $info_data = DB::table('fs_order_uploads_detail')->where('is_package', 0)->where('warehouse_id', $kho)->OrderBy('date_package', 'desc')->paginate(20);
        }
        else{
             $info_data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->where('warehouse_id', $kho)->OrderBy('date_package', 'desc')->paginate(20);
        }
           
        return view('admin.complete_order', compact('info_data'));
    }

    public function view_history_print()
    {
        $domain = "dienmayai.com";
        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: 7ojTLYXnzV0EH1wRGxOmvLFga",
                
            )
        ));

        $link_api ='https://api.'.$domain.'/api/show-data-order-new?warehouse_id=1';
       
        $response = file_get_contents($link_api, FALSE, $context);

        dd($response);
        return view('DetailsShopOrder.show_post_print');
    }

    protected function get_data_order_new()
    {
        $start = microtime(true);

        $redis = new \Redis();
        // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);  

      
        if(!$redis->exists('data_order_new_')){

            $domain = "dienmayai.com";
            $context = stream_context_create(array(
                'http' => array(
                    
                    'method' => 'GET',

                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                                "token: 7ojTLYXnzV0EH1wRGxOmvLFga",
                    
                )
            ));

            $link_api ='https://api.'.$domain.'/api/show-data-order-new?warehouse_id=1';
           
            $response = file_get_contents($link_api, FALSE, $context);

            // $data_convert = json_decode($response);

            $redis->set('data_order_new_', $response);
        }  

        $data = $redis->get('data_order_new_');

        echo"<pre>";

            print_r($data);    
        echo "</pre>";

        $end = microtime(true);
        $execution_time = $end - $start;

        echo "Thời gian thực thi: " . number_format($execution_time, 6) . " giây";
       
        // dd($data);
        
    }


    public function SearchDataOfUser(Request $request)
    {
        $date1 =  $request->date1;

        $date2 =  $request->date2;

        $option = $request->options;


        $startOfDay = Carbon::parse($date1)->startOfDay();
        $endOfDay = Carbon::parse($date2)->endOfDay();

        $user_package_id = $request->name;

        if(!empty($date1) && !empty($date2)){

            if(!empty($user_package_id)){
                $info_data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->where('user_package_id', $user_package_id)->whereBetween('date_package', [$startOfDay, $endOfDay])->orderBy('date_package', 'desc')->paginate(12);
            }
            else{
                $info_data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->whereBetween('date_package', [$startOfDay, $endOfDay])->orderBy('date_package', 'desc')->paginate(12);
            }
            
        }

        if($option==1 && !empty($info_data)){

            // Tạo đối tượng Spreadsheet mới
            $spreadsheet = new Spreadsheet();

            // Lấy sheet mặc định
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(80);
            $sheet->getColumnDimension('C')->setWidth(90);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(30);
            $sheet->getColumnDimension('G')->setWidth(30);
            $sheet->getColumnDimension('H')->setWidth(30);
            $sheet->getColumnDimension('I')->setWidth(30);
            $sheet->getColumnDimension('J')->setWidth(30);
            $sheet->getColumnDimension('K')->setWidth(30);

           


            $sheet->setCellValue('A1', 'STT');
            $sheet->setCellValue('B1', 'Tracking code');
            $sheet->setCellValue('C1', 'Tên sản phẩm');
            $sheet->setCellValue('D1', 'Tên shop');
            $sheet->setCellValue('E1', 'Mã shop');
            $sheet->setCellValue('F1', 'Số lượng');
            $sheet->setCellValue('G1', 'Id đơn hàng');
            $sheet->setCellValue('H1', 'Người đánh đơn');
            $sheet->setCellValue('I1', 'Ngày đánh đơn');
            $sheet->setCellValue('J1', 'Thời gian đóng đơn hàng');
            $sheet->setCellValue('K1', 'Thành tiền');

            $key=1;
            $stt =0;

            if(!empty($info_data)){

                foreach ($info_data as $item){
                    $key++;
                    $stt++;

                    
                   $sheet->setCellValue('A'.$key, $stt);      
                   $sheet->setCellValue('B'.$key, $item->tracking_code);  
                   $sheet->setCellValue('C'.$key, $item->product_name); 
                   $sheet->setCellValue('D'.$key, $item->shop_name);
                   $sheet->setCellValue('E'.$key, $item->shop_code);
                   $sheet->setCellValue('F'.$key, $item->count);
                   $sheet->setCellValue('G'.$key, $item->record_id);
                
                   $sheet->setCellValue('H'.$key, $item->user_package_id);
                   $sheet->setCellValue('I'.$key, date("d/m/Y", strtotime($item->date)));
                   $sheet->setCellValue('J'.$key, date("d/m/Y", strtotime($item->date_package)));
                   $sheet->setCellValue('K'.$key, number_format((float)$item->total_price, 0, ',', '.'));

                }
            }   

            // Tạo writer cho file Excel
            $writer = new Xlsx($spreadsheet);

            // Đặt các header HTTP để tải tệp xuống
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="data_pack.xlsx"');
            header('Cache-Control: max-age=0');

            // Gửi tệp Excel ra trình duyệt
            $writer->save('php://output');
            exit;

        }
        else{
            return view('admin.complete_order', compact('info_data'));
        }


    }


    public function viewShowOrder()
    {
        // $check = $_GET['check']??'';
        // if(!empty($check)){
        //     $sesion = Session()->get('admin');

        //     dd($sesion[0]);
        // }

        $sesion = Session()->get('admin');

        if($sesion[0] ==='adminmn'){
            $kho =2;
        }
        else{
            $kho =1;
        }
        $info_data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->where('warehouse_id', $kho)->OrderBy('date_package', 'desc')->paginate(10);
        return view('admin.show_complete_order', compact('info_data'));
    }

    public function post_complete_order(Request $request)
    {
        // check đơn đóng thuộc miền nam hay miền bắc theo user đăng nhập

        $sesion = Session()->get('admin');

        if($sesion[0] ==='adminmn'){
            $kho =2;
        }
        else{
            $kho =1;
        }

        $define_id = ['$'=>252, '@'=>253, '%'=>254,'?'=>255, '+'=>251, '&'=>9,'#'=>256, '*'=>257,'/'=>258,'>'=>259,'<'=>260,'-'=>266];

        $searchs =  $request->search;

        $active = $request->active;

         // phần kiểm tra kí tự để define người đóng đơn

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $update = false;

        dd($searchs);

        if(!empty($searchs) && $active==1):

            $set_ky_tu = ['$','@','%','?','+','&','#','*','/','>','<','-'];

            $kytudefine = substr(trim($searchs), -1);

            if(!in_array($kytudefine, $set_ky_tu)){
                $kytudefine = '&';
            }

            $user_id = $define_id[$kytudefine];

            $search = str_replace($kytudefine, '', $searchs);

            // nếu đóng đơn còn không là hoàn đơn
           
            $checkorders = DB::table('fs_order_uploads_detail')->select('id')->where('is_package', 0)->where('tracking_code', $search)->get()->last();


            if(!empty($checkorders)):

                    $checkorders_id = $checkorders->id;  // ID của đơn hàng (từ kết quả trước)
                    $user_package_id = $user_id; // Giá trị của $user_package_id

                    $update_pack = [
                        'is_package' => 1,
                        'user_package_id' => $user_package_id,
                        'date_package' => date("Y-m-d H:i:s"),
                       
                    ];

                    $update = DB::table('fs_order_uploads_detail')->where('id', $checkorders_id)->update($update_pack);



                    if ($update) {

                        $msg ='Đóng hàng thành công đơn hàng có id là '.$checkorders_id.' và có mã vận đơn là '.$search  ;

                        session()->flash('success', $msg);


                    } else {

                        $msg = 'Có lỗi trong quá trình đóng hàng'; 

                        session()->flash('error', $msg);

                    }
                        
            else:

                $msg = 'Đóng hàng không thành công, vui lòng kiểm tra lại mã đơn';

                session()->flash('error', $msg);


            endif;


        else:

            if($active ==0):
                $user_package_id = 9;
    
                

                $id =  $request->id;

                $checkorders_id = $id; 

                $data_update =  [
                    'is_package' => 0,
                   
                    'date_package' => date("Y-m-d H:i:s"),
                    
                ];

                $update =  DB::table('fs_order_uploads_detail')->where('id', $checkorders_id)->update($data_update);


                if ($update) {

                    $msg ='Hoàn thành công đơn hàng có id là '.$id;

                    session()->flash('success', $msg);

                    
                } else {
                    $msg ='Hoàn hàng thất bại';

                    session()->flash('error', $msg);

                }

            else:
            
                $msg ='mã trạng thái không đúng vui lòng kiểm tra lại';   

                session()->flash('error', $msg);

            endif;  

        endif; 

        return redirect()->back();

    }

    public function get_data_for_ajax_page(Request $request)
    {

        $redis = new \Redis();
        // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);  

        $kho = $request->kho;

        if($redis->exists('f5_kho_'.$kho) && $redis->get('f5_kho_'.$kho)==1){

            $redis->set('f5_kho_'.$kho, 0);

            return 1;


        }  
        else{
            return 0;
        }


        
    }

    public function get_data_to_pm()
    {

        $domain = "dienmayai.com";
        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: 7ojTLYXnzV0EH1wRGxOmvLFga",
                
            )
        ));

        $link_api ='https://api.'.$domain.'/api/show-data-order-new';
       
        $response = file_get_contents($link_api, FALSE, $context);

        $data_convert = json_decode($response);

        

        DB::table('fs_order_uploads_detail')->delete();

        for ($i=0; $i < 6000; $i++) { 

            DB::table('fs_order_uploads_detail')->insert((array)$data_convert[$i]);
           
        }

        $username = "admin";
        $password = "TTP_KAW2024";
        $data = ["username"=>$username, "password"=>$password, "action"=>"login"];
        // URL đăng nhập và trang đích
        $loginUrl = "https://dienmayai.com/admin/login.php";
        $targetUrl = "https://dienmayai.com/admin/index.php?module=order&view=order&task=update_by_api_packed";

        // Khởi tạo cURL session
        $ch = curl_init($loginUrl);

        // Thiết lập các tùy chọn cURL
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt'); // Lưu cookie

        // Thực hiện yêu cầu đăng nhập
        $loginResponse = curl_exec($ch);    

        // Kiểm tra đăng nhập thành công
        if (curl_errno($ch)) {
            echo 'Lỗi đăng nhập: ' . curl_error($ch);
        } else {
            // Thiết lập tùy chọn cho yêu cầu đến trang đích
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $targetUrl);
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt'); // Gửi cookie đã lưu

            // Thực hiện yêu cầu đến trang đích
            $targetResponse = curl_exec($ch);

            // Xử lý kết quả trả về từ trang đích
            if (curl_errno($ch)) {
                echo 'Lỗi truy cập trang đích: ' . curl_error($ch);
            } else {
                echo($targetResponse); // Hoặc xử lý kết quả theo ý muốn
            }
           
        }

        // Đóng cURL session
        curl_close($ch);


        echo "thành công";
        
    }

    public function insertDbUpload()
    {
        $domain = "dienmayai.com";
        $context = stream_context_create(array(
            'http' => array(
                
                'method' => 'GET',

                'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                            "token: 7ojTLYXnzV0EH1wRGxOmvLFga",
                
            )
        ));

        $link_api ='https://api.'.$domain.'/api/show-data-order-new';
       
        $response = file_get_contents($link_api, FALSE, $context);

        $redis = new \Redis();


        // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);

        $keyExists = $redis->exists('api_complete_box');

        $data_convert = json_decode($response);


        if ($keyExists) {
            $redis->del("api_complete_box");

        }   
        $redis->set("api_complete_box", $response);

        $this->get_data_to_pm();

        echo "thanh cong";
       
        // $info_data = collect(json_decode($response));

        // // dd(collect($info_data));

        // $page = request()->get('page', 1);  // or use any method to get the current page
        // $perPage = 10;  // Number of items per page

        // // Slice the collection to get the items for the current page
        // $items = $info_data->slice(($page - 1) * $perPage, $perPage)->values();

        // // Create the paginator
        // $paginator = new LengthAwarePaginator($items, $info_data->count(), $perPage, $page, [
        //     'path' => LengthAwarePaginator::resolveCurrentPath(), // Ensure correct pagination links
        // ]);

        // // Use the paginator in a view or controller
        // dd($paginator);

    }
}
