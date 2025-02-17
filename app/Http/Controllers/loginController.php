<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;

class loginController extends Controller
{
    public function index(){
        
        if (Session::has('admin')) {
            
            return view('admin.admin');
            //
        }else{
            
            return view('login');
        }
       
    }  


    public function logout(Request $request)
    {
        $request->session()->forget('admin');

        return redirect(route('home'));
    }    
    public function viewLogin()
    {
        if (Session::has('admin')) {
            
            return redirect(route('dong don'));
            //
        }else{
            
            return view('welcome');
        }
    }
    
    public function login(Request $request){
        
        $username =  trim($request->username);
        
        $password = trim($request->password);
        
        if($username ==='adminapi' && $password ==='123456123'||$username ==='adminmn' && $password ==='123456123'){
            
            
            $data = [$username];
            
            $request->session()->put('admin', $data);
            
            return redirect(route('dong don'));
            
            
        }
        else{
            return abort('403');
        }
    }
    
    public function view_admin(){
        
        if (Session::has('admin')) {
            
            return view('admin.admin');
            //
        }else{
            
            return redirect(route('view-login'));
        }
        
        
        
    }
    
    
    
}
