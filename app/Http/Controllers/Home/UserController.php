<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * 加载添加用户首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addIndex(){
        $view['department'] =   $this->getDepartment();
        $view['channel']    =   $this->getChannel();
        return view("home.user.adduser",$view);
    }
    /**
     * post请求  实现添加用户
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addUser(Request $request){
        $data   =   $request->input();
        $end    =   createtabledata('user',$data);
        if (!$end){return $this->jumpUrl('没有任何数据，非法提交');}
        $end['addtime'] =   time();
        $end['time_id'] =   date("Ymd",time());
        $end['admin_id']    =   session('user_id_login');
        $num    =   DB::table('user')->insertGetId($end);
        if ($num){
            $where['username']   =   $end['username'];
            $where['tel']   =   $end['tel'];
            DB::table('user_custom')->where(data2where($where))->update(['user_id'=>$num]);
            return $this->jumpUrl('添加成功',url('/home/page/user/adduser'));
        }else{
            return $this->jumpUrl('添加失败');
        }
    }
    /**
     * 加载用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList(){
        $view['user']   =   DB::table('user')->orderBy('id','desc')->paginate(15);
        return view('home.user.userlist',$view);
    }
    /**
     * 通过用户id获取用户详细信息
     * @param $id
     * @param $token
     */
    public function getUserInfoById($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $user   =   DB::table('user')->where("id","=",$id)->first();
        $channel    =   DB::table("channel")->where("id","=",$user->channel_id)->pluck("name");
        $department =   DB::table("department")->where("id","=",$user->department_id)->pluck("name");
        $user->channel_id  =  $channel[0];
        $user->department_id = $department[0];
        $user->addtime     =   date("Y-m-d H:i:s",$user->addtime);
        $user->gander      =   $user->gander?"男":"女";
        $user->adminname    =   getAdminNameById($user->admin_id);
        return ajaxReturn(object_to_array($user),"请求成功",0);
    }
    /**
     * 通过用户id获取用户详细原始信息
     * @param $id
     * @param $token
     */
    public function getUserInfoByIdData($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $user   =   DB::table('user')->where("id","=",$id)->first();
        return ajaxReturn(object_to_array($user),"请求成功",0);
    }
    /**
     * 删除用户
     * @param $id
     * @param $token
     */
    public function userDel($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $num    =   DB::table("user")->delete($id);
        if ($num > -1){
            return ajaxReturn([],"删除成功",0);
        }else{
            return ajaxReturn([],"删除失败",1);
        }
    }
    /**
     * 根据条件查询用户列表
     * @param Request $request
     */
    public function search(Request $request){
        $data   =   $request->input();
        $k    =   $data['name'];
        $v  =   $data['value'];
        session(['search_user_value'=>$v]);
        switch ($k){
            case 'username':
                return ajaxReturn(['url'=>'username'],'请求成功',0);
                break;
            case 'age':
                return ajaxReturn(['url'=>'age'],'请求成功',0);
                break;
            case 'addr':
                return ajaxReturn(['url'=>'addr'],'请求成功',0);
                break;
            case 'addtime':
                return ajaxReturn(['url'=>'time'],'请求成功',0);
                break;
            case 'tel':
                return ajaxReturn(['url'=>'tel'],'请求成功',0);
                break;
            default :
                return ajaxReturn([],'错误的请求方式',1);
        }
    }
    /**
     * 加载打印当前用户页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dumpUser(){
        $view['department']    =   $this->getDepartment();
        $view['channel']        =   $this->getChannel();
        return view('home.user.dumpuser',$view);
    }
    /**
     * 根据post提交的数据输出用户excel
     * @param Request $request
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function dumpUserList(){
        $message    =   Cache::get("userDumpMessage");
        $header     =   $message['header'];
        $result     =   $message['users'];
        $dump   =   new ExportController();
        $dump->export($header,$result,"登记客户导出");
    }
    public function showDumpUserList(Request $request){
        $data   =   $request->input();
        $dump   =   new ExportController($data);
        $result =   $dump->dataReturn();
        $header =   $dump->getHeader();
        $view['users']  =   $result;
        $view['header'] =   $header;
        Cache::rememberForever("userDumpMessage",function () use ($view){
            return $view;
        });
        return view('home.user.showdump',$view);
    }
    /**
     * 根据请求导出管理员列表
     */
    public function dumpAdmin(){
        return ajaxReturn([],"该功能现在还没有完善，请之后再用",1);
    }
    /**
     * 管理员添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAdminIndex (){
        $view['role']   =   DB::table('role')->get()->toArray();
        $view['role']   =   data2menu($view['role']);
        return view("home.user.addadmin",$view);
    }
    /**
     * post提交数据 添加管理员
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function addAdmin(Request $request){
        $data   =   $request->post();
        if($this->judgeSameName($data['username'])){
            return ajaxReturn([],"已存在账号{$data['username']},请重新起名",1);
        };
        if (isset($data['role_id'])){
            $role_id = "";
            foreach ($data['role_id'] as $v){
                $role_id .= $v.",";
            }
            $role_id = rtrim($role_id, ",");
        }
        $data['role_id']    =   $role_id;
        $end    =   createtabledata('admin',$data);
        $end['password']  =   md5($end['password']."asdfghjkl");
        $end['safe']      =   substr($end['password'],3,8);
        $end['status']    = 1;
        $end['tel']    = 1;
        $end['pid'] =   session("user_id_login");
        $end['addtime'] =   time();
        $num    =   DB::table('admin')->insert($end);
        if ($num){
            return $this->jumpUrl("添加成功");
        }else{
            return $this->jumpUrl("添加出错请联系管理员");
        }
    }
    /**
     * 加载管理员列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminList(){
        $view['admin']  =   DB::table("admin")->get();
        return view("home.user.adminlist",$view);
    }
    /**
     * 根据用户名查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListByUsername(){
        $value  =   session('search_user_value');
        $view['user']   =   DB::table('user')->where('username','like',"%$value%")->orderBy('id','desc')->paginate(10);
        return view('home.user.userlist',$view);
    }
    /**
     * ajax根据用户名查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAjaxUserListByUsername(){
        $value  =   session('search_user_value');
        $view['user']   =   DB::table('user')->where('username','like',"%$value%")->orderBy('id','desc')->paginate(10);
        return view('home.user.search',$view);
    }
    /**
     * 根据用户地址查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListByAddr(){
        $value  =   session('search_user_value');
        $view['user']   =   DB::table('user')->where('addr','like',"%$value%")->orderBy('id','desc')->paginate(10);
        return view('home.user.userlist',$view);
    }
    /**
     * 根据用户年龄查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListByAge(){
        $value  =   session('search_user_value');
        $view['user']   =   DB::table('user')->where('age','=',"$value")->orderBy('id','desc')->paginate(10);
        return view('home.user.userlist',$view);
    }
    /**
     * 根据添加时间段查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListByTime(){
        $value  =   session('search_user_value');
        $time   =   explode("-",$value);
        $time1  =   $this->timeString($time[0],$time[1],$time[2]);
        $time2  =   $this->timeString($time[3],$time[4],$time[5]);
        $view['user']   =   DB::table('user')->whereBetween('addtime',[$time1,$time2])->orderBy('id','desc')->paginate(10);
        return view('home.user.userlist',$view);
    }
    /**
     * 根据用户的电话号码查询用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListByTel(){
        $value  =   session('search_user_value');
        $view['user']   =   DB::table('user')->where('tel','=',"$value")->orderBy('id','desc')->paginate(10);
        return view('home.user.userlist',$view);
    }
    /**
     * 查询来诊科室数据整理
     * 默认获取本月的数据
     */
    public function departmentList(Request $request){
        $department =   $this->getDepartment();
        $data   =   $request->input();
        if (isset($data['time_id']) && $data['time_id']){
            $time   =   $data['time_id'];
        }else{
            $time   =   'Ym';
        }
        $sql   =    "select time_id from `scafel_user` where admin_id = 2 and time_id >= '".date($time."01",time())."' and time_id <= '".date($time."t",time())."' group by `time_id` ";
        $user   =   DB::select($sql);
        $number =   [];
        foreach ($user as $key=>$value){
            $total[$value->time_id] =   0;
            foreach ($department as $k=>$v){
                $sql    =   "select count(id) as all_id from `scafel_user` where admin_id = 2  and time_id = {$value->time_id} and department_id = {$v->id}";
                $one    =   DB::selectOne($sql);
                $end[$value->time_id][$v->id]    =   $one->all_id;
                $total[$value->time_id]  +=  $one->all_id;
                if (isset($number[$k])){
                    $number[$k] +=  $one->all_id;
                }else{
                    $number[$k] =  $one->all_id;
                }
            }
        }
        $view['department'] =   $department;
        $view['user']   =   isset($end)?$end:'null';
        $view['time']   =   $user;
        $view['total_cos']  =   isset($total)?$total:'null';
        $view['number'] =   $number;
        $view['number1']    =   0;
        $view['number2']    =   0;
        $view['total_all']  =   array_sum($number);
        if (isset($data['is_show']) && $data['is_show']){
            $header[]   =   '时间';
            foreach ($department as $value){$header[]  =   $value->name;}
            $header[]   =   '合计';
            $result =   [];
            foreach ($user as $key=>$value){
                $result[$key][] =  $value->time_id;
                foreach($end[$value->time_id] as $v){
                    $result[$key][] =   $v;
                }
                $result[$key][] =   $total[$value->time_id];
            }
            $result[$key+1][]    =  '合计';
            foreach($number as $v){
                $result[$key+1][]   =   $v;
            }
            $result[$key+1][]   = array_sum($number);
            $dump   =   new ExportController();
            return $dump->export($header,$result,"就诊科室数据整理导出");
        }else{
            return view('home.user.departmentlist',$view);
        }
    }
    public function departmentSearch(Request $request){
        $data   =   $request->input('value');
        $time   =   str_replace("-","",$data);
        return ajaxReturn(array('time_id'=>$time),"请求成功",0);
    }
    /**
     * 查询来诊渠道数据整理
     */
    public function channelList(Request $request){
        $channel =   $this->getChannel();
        $data   =   $request->input();
        if (isset($data['time_id']) && $data['time_id']){
            $time   =   $data['time_id'];
        }else{
            $time   =   'Ym';
        }
        $sql   =    "select count(id),time_id from `scafel_user` where admin_id = 2  and  time_id >= '".date($time."01",time())."' and time_id <= '".date($time."t",time())."' group by `time_id` ";
        $user   =   DB::select($sql);
        $number =   [];
        foreach ($user as $key=>$value){
            $total[$value->time_id] =   0;
            foreach ($channel as $k=>$v){
                $sql    =   "select count(id) as all_id from `scafel_user` where admin_id = 2  and time_id = {$value->time_id} and channel_id = {$v->id}";
                $one    =   DB::selectOne($sql);
                $end[$value->time_id][$v->id]    =   $one->all_id;
                $total[$value->time_id]  +=  $one->all_id;
                if (isset($number[$k])){
                    $number[$k] +=  $one->all_id;
                }else{
                    $number[$k] =  $one->all_id;
                }
            }
        }
        $view['channel'] =   $channel;
        $view['user']   =   isset($end)?$end:'null';
        $view['time']   =   $user;
        $view['total_cos']  =   isset($total)?$total:'null';
        $view['number'] =   $number;
        $view['number1']    =   0;
        $view['number2']    =   0;
        $view['total_all']  =   array_sum($number);
        if (isset($data['is_show']) && $data['is_show']){
            $header[]   =   '时间';
            foreach ($channel as $value){$header[]  =   $value->name;}
            $header[]   =   '合计';
            $result =   [];
            foreach ($user as $key=>$value){
                $result[$key][] =  $value->time_id;
                foreach($end[$value->time_id] as $v){
                    $result[$key][] =   $v;
                }
                $result[$key][] =   $total[$value->time_id];
            }
            $result[$key+1][]    =  '合计';
            foreach($number as $v){
                $result[$key+1][]   =   $v;
            }
            $result[$key+1][]   = array_sum($number);
            $dump   =   new ExportController();
            return $dump->export($header,$result,"来诊渠道数据整理导出");
        }else{
            return view('home.user.channellist',$view);
        }
    }
    /**
     * 用来处理时间
     * @param Request $request
     */
    public function channelSearch(Request $request){
        $data   =   $request->input('value');
        $time   =   str_replace("-","",$data);
        return ajaxReturn(array('time_id'=>$time),"请求成功",0);
    }
    /**
     * 检查同名用户是否存在
     * @param $name
     * @return bool
     */
    private function judgeSameName($name){
        $user   =   DB::table('admin')->where("username",'=',$name)->first();
        return empty($user)?false:true;
    }
    /**
     * 数据统计中查询用户列表信息
     * @param Request $request
     * @param $type
     */
    public function getUserListByType(Request $request){
        $data   =   $request->input();
        $where  =   [];
        $time   =   mb_substr($data['time_id'],0,6);
        if($data['time'] == -1 && $data['id'] == -1){//整月统计数据
            $where = "time_id>='".date($time."01",time())."' and time_id <= '".date($time."t",time())."'";
        }elseif ($data['time'] == -1){//单日全科统计
            $where = "{$data['type']}_id = {$data['id']} and time_id>='".date($time."01",time())."' and time_id <= '".date($time."t",time())."'";
        }elseif($data['id'] == -1){//单科整月统计
            $where  =   "time_id = {$data['time']}";
        }else{//单日单科
            $where = "time_id = '{$data['time']}' and {$data['type']}_id = {$data['id']}";
        }
        $sql    =   "SELECT id,username,tel,department_id,channel_id FROM scafel_user WHERE  admin_id = 2 and ".$where;
        $message    =   DB::select($sql);
        $view['users']  =   $message;
        return view("home.user.searchtype",$view);
    }
    /**
     * 等级分配中选择用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserListForSelect(){
        $view['user']   =   DB::table('user')->where('class_id','=',0)->orderBy('id','desc')->select('id','username','addtime','tel','class_id','department_id','channel_id')->paginate(15);
        return view('home.user.usershow',$view);
    }
    public function findCustomUser(Request $request){
        $data   =   $request->input();
        $where[$data['name']]   =   $data['value'];
        $message    =   DB::table('user_custom')->where(data2where($where))->count('id');
        return ajaxReturn(['code'=>$message],'获取成功',1);
    }
    public function findCustomUserMessage(Request $request){
        $data   =   $request->input();
        $where[$data['name']]   =   $data['value'];
        $where['user_id']   =   0;
        $message    =   DB::table('user_custom')->where(data2where($where))->paginate(15);
        $view['users']  =   $message;
        return view('home.user.searchcustom',$view);
    }
    public function findCustomUserInfo(Request $request){
        $id   =   $request->input('id');
        $message    =   DB::table('user_custom')->find($id);
        return ajaxReturn(['user'=>$message],'获取信息成功',1);
    }
}
