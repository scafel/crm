<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(){
        return view("mobile.login.login");
    }
    public function doLogin(Request $request){
        $data   =   $request->input();
        if (empty($data) || !isset($data['name']) || !isset($data['password']) || empty($data['name']) || empty($data['password'])){
            return ajaxReturn([],'用户名和密码不能为空',1);
        }
        $condition['username']  =   $data['name'];
        $condition['password']  =   md5($data['password']."asdfghjkl");
        $condition['safe']      =   substr($condition['password'],3,8);
        $condition['status']    =   1;
        $end    =   createtabledata('admin',$condition);
        $num    =   DB::table('admin')->where(data2where($end))->first();
        if (empty($num)){
            return ajaxReturn([],'用户名和密码错误',1);
        }else{
            session(['user_id_login'=>$num->id,'admin'=>$num]);
            return ajaxReturn((array)$num,'登陆成功,正在跳转至主页',0);
        }
    }
    public function logout(Request $request){
        $request->session()->forget('user_id_login');
        $request->session()->flush();
        if (session('user_id_login')){
            return ajaxReturn([],'退出失败，请联系管理员',1);
        }else{
            return ajaxReturn([],'退出成功',0);
        }
    }
}
