<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;

class adminController extends Controller
{
    public function loginAdminUser(Request $request)
    {


       $username =  trim($request->username);
        
        $password = trim($request->password);
        
        if($username ==='adminapi' && $password ==='123456123'||$username ==='adminmn' && $password ==='123456123'){
            
            $data = [$username];
            
            $request->session()->put('admin', $data);
            
            return redirect(route('dong don'));
            
            
        }
        else{
            $msg = 'user hoặc mật khẩu không đúng, vui lòng kiểm tra lại';

            session()->flash('error', $msg);

            return redirect()->back(); 
        }
       
    }
}
