<?php

namespace App\Http\Controllers;

use http\Env\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 读取管理员权限及权限列表
     * @return array|void
     */
    public function userRoleById(){
        $admin  =   session("admin");
        $role   =   explode(",",$admin->role_id);
        if (empty($role)){ return ajaxReturn([],'没有任何执行权限',1); }
        foreach ($role as $value){
            $roleAll[] =   DB::table('role')->where('id',$value)->first();
        }
        return data2menu($roleAll);
    }

    /**
     * 获取就诊科室列表
     * @return mixed
     */
    public function getDepartment(){
        return Cache::rememberForever("departmentController",function(){
            return DB::table('department')->where('status','=',1)->get();
        });
    }

    /**
     * 获取来诊渠道列表
     * @return mixed
     */
    public function getChannel(){
        return Cache::rememberForever("channelController",function(){
            return DB::table('channel')->where('status','=',1)->get();
        });
    }

    public function getClass(){
        return Cache::rememberForever("classController",function(){
            return DB::table('class')->where('status','=',1)->get();
        });

    }

    /**
     * 根据id获取科室名称
     * @param array $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getDepartmentNameById(array $id){
        return Cache::rememberForever("getDepartmentNameById",function() use($id){
            return DB::table('department')->whereIn('id',$id)->find('name');
        });
    }

    /**
     * 根据id获取来诊渠道名称
     * @param array $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getChannelNameById(array $id){
        return Cache::rememberForever("getChannelNameById",function() use($id){
            return DB::table('channel')->whereIn('id',$id)->find('name');
        });
    }
    /**
     * 根据id获取科室名称一个
     * @param int $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getDepartmentNameByIdOne(int $id){
        return Cache::rememberForever("getDepartmentNameByIdOne".$id,function() use($id){
            $message    =   DB::table('department')->find($id);
            return $message->name;
        });
    }

    /**
     * 根据id获取来诊渠道名称单个
     * @param int $id
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function getChannelNameByIdOne(int $id){
        return Cache::rememberForever("getChannelNameByIdOne".$id,function() use($id){
            $message    =   DB::table('channel')->find($id);
            return $message->name;
        });
    }

    public function getAdminNameById($id){
        return Cache::rememberForever("getAdminNameById".$id,function() use($id){
            $message    =   DB::table('admin')->find($id);
            return $message->username;
        });
    }

    /**
     * 时间函数处理
     * @param string $y 年
     * @param string $m 月
     * @param string $d 日
     * @param string $h 时
     * @param string $i 分
     * @param string $s 秒
     * @return int
     */
    public function timeString($y = '0000',$m = '00',$d = '00',$h = '00',$i = '00',$s ='00'){
        if (mb_strlen($m) > 1){}else{ $m = '0'.$m;}
        if (mb_strlen($d) > 1){}else{ $d = '0'.$d;}
        if (mb_strlen($h) > 1){}else{ $h = '0'.$h;}
        if (mb_strlen($i) > 1){}else{ $i = '0'.$i;}
        if (mb_strlen($s) > 1){}else{ $s = '0'.$s;}
        return gmmktime((int) $h,(int) $i,(int) $s,(int) $m,(int) $d,(int) $y);
    }

    /**
     * 检查重名
     * @param Request $request
     * @param $token
     */
    public function checkDuplicateName(Request $request,$token){
        if (!idMd5Token(0,$token)){return ajaxReturn([],'数据验证异常',1);}
        $table  =   $request['table'];
        $name   =   $request['index'];
        $value  =   $request['value'];
        $num    =   DB::table($table)->where($name,"=",$value)->first();
        if (empty($num)){
            return ajaxReturn([],'数据正常',0);
        }else{
            return ajaxReturn([],'已存在该值，请确认重新输入',1);
        }
    }

    /**
     * 页面跳转提示页
     * @param null $message
     * @param null $url
     * @param int $waitsecond
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jumpUrl($message = null,$url = null,$waitsecond = 3){
        $view['message']    =   $message ?? '操作错误';
        $view['jumpUrl']    =   $url ?? $_SERVER['HTTP_REFERER'];
        $view['waitSecond'] =   $waitsecond ?? 1;
        return view('tips',$view);
    }

    /**
     * 消息提示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tips(){
        $view['message']    =   '操作错误';
        $view['jumpUrl']    =   "#";
        $view['waitSecond'] =   3;
        return view("tips",$view);
    }
    /**
     * 消息提示,后关闭窗口
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($message){
        return view("home.common.tips",['message'=>$message]);
    }

}
