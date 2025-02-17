<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class orderController extends Controller
{
    public function getdata()
    {
    	$user_package_id = $_GET['id_user']??'';

    	if(!empty($user_package_id)):

    		$data  = DB::table('fs_order_uploads_detail')->orderBy('date_package', 'desc')->where('user_package_id', $user_package_id)->where('is_package',1)->paginate(12)->toArray();
        else:
        	$data  = DB::table('fs_order_uploads_detail')->orderBy('date_package', 'desc')->where('is_package',1)->paginate(12)->toArray();	

        endif;

    	if(!empty($data)):

    		return response($data);
    	else:
    	
    		return [];
    	endif;		

    }

    public function show_data_packed(Request $request)
    {
    	$redis = new \Redis();


        // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);   

        $update_data_packed = json_encode([]);

        $keyExists = $redis->exists('update_data_packed');

        if ($keyExists) {
            $update_data_packed = $redis->get("update_data_packed");

        }    

        return $update_data_packed;
    }

    public function delete_redis_packed(Request $request)
    {
    	$redis = new \Redis();


        // Thiết lập kết nối
        $redis->connect('127.0.0.1', 6379);   

        

        $keyExists = $redis->exists('update_data_packed');

        if ($keyExists) {
            $update_data_packed = $redis->del("update_data_packed");

        }    

        
    }


    public function SearchDataOfUser(Request $request)
    {
    	$date1 =  $request->date1;

    	$date2 =  $request->date2;


		$startOfDay = Carbon::parse($date1)->startOfDay();
		$endOfDay = Carbon::parse($date2)->endOfDay();

		

    	$user_package_id = $request->name;

    	if(!empty($date1) && !empty($date2)){

    		if(!empty($user_package_id)){
    			$data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->where('user_package_id', $user_package_id)->whereBetween('date_package', [$startOfDay, $endOfDay])->orderBy('date_package', 'desc')->paginate(12)->toArray();
    		}
    		else{
    			$data = DB::table('fs_order_uploads_detail')->where('is_package', 1)->whereBetween('date_package', [$startOfDay, $endOfDay])->orderBy('date_package', 'desc')->paginate(550)->toArray();
    		}
    		
    		

	    	if(!empty($data)):

	    		return response($data);
	    	else:
	    	
	    		return [];
	    	endif;		
    	}

    	

    }

    public function SearchDataOfOrder(Request $request)
    {
    	$date1 =  $request->date1;

    	$date2 =  $request->date2;


		$startOfDay = Carbon::parse($date1)->startOfDay();
		$endOfDay = Carbon::parse($date2)->endOfDay();

		

    	if(!empty($date1) && !empty($date2)){
    		
    		$data = DB::table('fs_order_uploads')->whereBetween('created_time', [$startOfDay, $endOfDay])->orderBy('id', 'desc')->paginate(10)->toArray();

	    	if(!empty($data)):

	    		return response($data);
	    	else:
	    	
	    		return [];
	    	endif;		
    	}

    }

    public function searchDataToCodeOrder(Request $request)
    {
    	$search =  trim($request->search);
    	if(!empty($search)){

    		$data = DB::table('fs_order_uploads')
            ->join('fs_order_uploads_detail', 'fs_order_uploads.id', '=', 'fs_order_uploads_detail.user_id')->where('fs_order_uploads_detail.code',$search)->OrWhere('fs_order_uploads_detail.tracking_code', $search)
            ->get()->toArray();
    		
    	}	

    	if(!empty($data)):

    		return response($data);
    	else:
    	
    		return [];
    	endif;	
    }



    public function searchDataOrder(Request $request)
    {
    	date_default_timezone_set('Asia/Ho_Chi_Minh');

    	if(!empty($request->search)):

    		$active =  $request->active;

    		if($active ==1):

		    	$clearData = trim($request->search);

		        $clearData = strip_tags($clearData);

		        $search = $clearData; 

		        $user_package_id = $request->user_package_id;
		        	
		        $checkorders = DB::table('fs_order_uploads_detail')->OrderBy('id','desc')->limit(100)->select('id')->where('is_package', 0)->where('tracking_code', $search)->get()->last();

		     
		        if(!empty($checkorders)):

				    $update = DB::table('fs_order_uploads_detail')->where('id', $checkorders->id)->update(['is_package'=>1,'user_package_id'=>$user_package_id, 'date_package'=>date("Y-m-d H:i:s")]);

				        
			        
			        return response('Đóng hàng thành công đơn hàng có mã vận đơn: '.$search);
		        else:
		       		return response('Đóng hàng không thành công, vui lòng kiểm tra lại mã đơn');
			    endif;	 	


		    else:

		    	if($active ==0):
			    	$id = $request->search;

			    	$update = DB::table('fs_order_uploads_detail')->where('id', $id)->update(['is_package'=>0,'user_package_id'=>NULL, 'date_package'=>NULL]);
			    	return response('Hoàn thành công đơn hàng');

			    endif;	

			    return response('lỗi');

		    endif;    	

	    endif; 

	    // return response(1);   

    }
}
