<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(){
        return view("home.login.login");
    }
    public function doLogin(Request $request){
        $data   =   $request->input();
        if (empty($data) || !isset($data['name']) || !isset($data['password']) || empty($data['name']) || empty($data['password'])){
            return $this->jumpUrl('用户名密码不能为空');
        }
        if (session('captcha') != $data['code']){
            return $this->jumpUrl('验证码错误');
        }
        $condition['username']  =   $data['name'];
        $condition['password']  =   md5($data['password']."asdfghjkl");
        $condition['safe']      =   substr($condition['password'],3,8);
        $condition['status']    =   1;
        $end    =   createtabledata('admin',$condition);
        $num    =   DB::table('admin')->where(data2where($end))->first();
        if (empty($num)){
            return $this->jumpUrl('用户名或密码错误');
        }else{
            session(['user_id_login'=>$num->id,'admin'=>$num]);
            return $this->jumpUrl('登陆成功',url('home/page'));
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
