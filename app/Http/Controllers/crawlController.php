<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;

class crawlController extends Controller
{


    public function crawPostTinhTe()
    {

        $crawl = DB::table('link_crawl')->get();

        $now   = Carbon::now();

        foreach ($crawl as $link) {

            $update_link = DB::table('link_crawl')->where('id', $link->id)->where('active', 0)->update(['active'=>1]);

            $html = file_get_html($link->link);

            $title = strip_tags($html->find('.entry-title', 0));

            $content = html_entity_decode($html->find('.page-content', 0));

            $insert = DB::table('post_crawl')->insert(['title'=>$title, 'content'=>$content, 'created_at'=>$now, 'updated_at'=>$now]);
        }
        echo "thành công";
       

    }


    public function vietnameseToEnglish($text) {
        // Chuyển đổi dấu câu
        $text = preg_replace('/[,\.;?!]/u', '.', $text);

        // Chuyển đổi chữ có dấu
        $patterns = array(
            '/[áàảãạăắằẳẵặâấầẩẫậ]/u',
            '/[éèẻẽẹêếềểễệ]/u',
            '/[óòỏõọôốồổỗộơớờởỡợ]/u',
            '/[íìỉĩị]/u',
            '/[úùủũụưứừửữự]/u',
            '/[ýỳỷỹỵ]/u',
            '/đ/u'
        );
        $replacements = array('a', 'e', 'o', 'i', 'u', 'y', 'd');
        $text = preg_replace($patterns, $replacements, $text);

        // Chuyển đổi từ/cụm từ (ví dụ)
        $text = str_replace('xin chào', 'hello', $text);

        return $text;
    }

    public function isLinkActive($url) {
        $ch = curl_init($url); // Khởi tạo cURL session

        // Thiết lập các tùy chọn cURL
        curl_setopt($ch, CURLOPT_NOBODY, true); // Chỉ lấy header, không lấy nội dung
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Theo dõi chuyển hướng
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Trả về kết quả dưới dạng chuỗi
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Thời gian chờ tối đa 10 giây

        curl_exec($ch); // Thực hiện yêu cầu

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Lấy mã trạng thái HTTP

        curl_close($ch); // Đóng cURL session

        // Kiểm tra mã trạng thái
        if ($httpCode >= 200 && $httpCode < 300) {
            return true; // Liên kết hoạt động
        } else {
            return false; // Liên kết không hoạt động
        }
    }

    public function convert_name_files()
    {
        $files = DB::table('check_error_pdf')->select('record_id','file')->get();

        foreach ($files as $key => $value) {
            
            $name =  substr(basename($value->file), 1);

            $links = basename($value->file)[0];

            if($links=='t'){

                $new_link = str_replace(basename($value->file), $name, $value->file);
            }
            else{
                $new_link = $value->file;
            }

    

            $check = $this->isLinkActive($new_link);

            if(!$check){

                echo 'record_id = '.$value->record_id.' và link là '.$new_link.' không tìm thấy'."\n";

            }   


        }
    }

    public function convert_name_file()
    {
        $data = DB::table('fs_order_uploads')->select('file_pdf','id')->whereBetween('id', [189222, 199425])->get();
        $dem = 0;



        foreach ($data as $key => $value) {

            $find = strpos($value->file_pdf, ",");

           

            if($find !== false){
                $link = explode(',', $value->file_pdf);

              
                if(!empty($link[0])){

                    $link_1 = $link[0];

                    $name_link1 = basename($link_1);


                    $path = str_replace($name_link1, '', $link_1);

                    

                    foreach ($link as $key => $vals) {

                        $vals_link = $vals;

                        if($key!=0){

                            $vals_link = $path.$vals;

                        }


                        $url = str_replace('files/orders/2024', 'https://cachsuadienmay.vn/public/uploads', $vals_link);

                        $url = str_replace('.pdft','.pdf', $url);

                        $check = $this->isLinkActive($url);

                        if(!$check){
                            $dem++;
                            $insert = ['file'=>$url, 'record_id'=>$value->id];
                            DB::table('check_error_pdf')->insert($insert);
                            echo $dem."\n";

                        }
                        
                    }

                }
               

               
            }
            else{
                $link =  $value->file_pdf;

                $url = str_replace('files/orders/2024', 'https://cachsuadienmay.vn/public/uploads', $link);

                $check = $this->isLinkActive($url);

                if(!$check){
                    $dem++;
                    $insert = ['file'=>$url, 'record_id'=>$value->id];
                    DB::table('check_error_pdf')->insert($insert);
                    echo $dem.'\n';

                }

            }
            
        }
        echo "thanh cong";

        
    }



    public function getLink()
    {

        $html = file_get_html('https://thegioidieuhoa.com.vn/danhmuc/tin-tuc-dieu-hoa/');

        $href = $html->find('.elementor-post__title a');

        foreach ($href as $key => $value) {

            DB::table('link_crawl')->insert(['link'=>$value->getAttribute('href'), 'active'=>0]);
           
        }
        echo "thành công";

    }

    public function viewListCrawl()
    {
        return view('crawl-table');
    }

    public function viewDetail($id)
    {

        $data = DB::table('post_crawl')->where('id', $id)->first();
        if(!empty($data)){
            return view('post-detail', compact('data'));
        }
        return abort('404');
        
    }
}
