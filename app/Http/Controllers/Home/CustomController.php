<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 客户系统  客服类控制器
 * Class CustomController
 * @package App\Http\Controllers\Home
 */
class CustomController extends Controller
{
    /**
     * 加载客服页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view("custom.index");
    }

    /**
     * @intention 加载点击过出院患者列表
     * @functionName customOutHisList
     * @creatTime 2019/10/26-11:40
     */
    public function customOutHisList(){
        if (session("user_id_login") != 1){
            $array['custom.admin_id']  =   session("user_id_login");
            $array['custom.status']    =   1;
        }
        $where  =   array(
            ['outtime','>',0]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view("home.custom.outtime",$view);
    }
    /**
     * 加载服务回访列表       住院回访
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customList(){
        if (session("user_id_login") != 1){
            $array['custom.admin_id']  =   session("user_id_login");
            $array['custom.status']    =   1;
        }
        $array['type']  =   1;
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where(data2where($array))->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view("home.custom.index",$view);
    }
    /**
     * 住院回访添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAdd(){
        $view['department'] =   $this->getDepartment();
        $view['channel']    =   $this->getChannel();
        $view['class']  =   $this->getClass();
        return view("home.custom.add",$view);
    }

    /**
     * 住院回访添加结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAddResult(Request $request){
        $data   =   $request->input();
        if ($data['user_id'] && $data['username']){
            $where['id']    =   $data['user_id'];
            $where['username']  =   $data['username'];
            $num = DB::table("user")->where(data2where($where))->count("id");
            if (!$num){return $this->jumpUrl("验证失败，请不要随意修改网站内容");}
            DB::table("user")->where(data2where($where))->update(['class_id'=>$data['class_id']]);
            $user['user_id']    =   $data['user_id'];
            $user['username']   =   $data['username'];
        }else{
            $end    =   createtabledata('user',$data);
            if (!$end){return $this->jumpUrl('没有任何数据，非法提交');}
            $end['addtime'] =   time();
            $end['time_id'] =   date("Ymd",time());
            $end['remarks'] =   "客服住院服务添加用户";
            $end['admin_id']    =   session('user_id_login');
            $num    =   DB::table('user')->insertGetId($end);
            if ($num){
                $user['user_id']    =   $num;
                $user['username']   =   $data['username'];
            }else{
                return $this->jumpUrl("用户添加失败？，请联系管理员");
            }
        }
        if (empty($user)){
            return $this->jumpUrl("系统错误，请联系管理员");
        }
        if($endCustom  =   createtabledata("custom",$data)){
            $endCustom['username']  =   $user['username'];
            $endCustom['user_id']   =   $user['user_id'];
            $endCustom['admin_id']  =   session("user_id_login");
            $endCustom['addtime']   =   time();
            $endCustom['type']   =   1;
            $time   =   explode("-",$data['nexttime']);
            $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
            $endCustom['remarks']   =   time()."|||".$data['lastremarks']."|||".$endCustom['nexttime'];
            $time   =   explode("-",$data['intime']);
            $endCustom['intime'] =   $this->timeString($time[0],$time[1],$time[2]);
            $endNotepad['fromid']   =   0;
            $endNotepad['toid']     =   session("user_id_login");
            $endNotepad['message']  =   "您预约了".$data['nexttime']."的住院首次服务回访 -》 ".$user['username'];
            $endNotepad['addtime']  =   time();
            $endNotepad['runtime']  =   $endCustom['nexttime'];
            DB::beginTransaction();
            $num1 = DB::table("custom")->insertGetId($endCustom);
            $endNotepad['custom_id']    =   $num1;
            $num2 = DB::table("notepad")->insert($endNotepad);
            if ($num1 && $num2){
                DB::commit();
                return $this->jumpUrl("操作成功，请时刻关注您的消息栏,消息会在提前一天给您传递");
            }else{
                DB::rollBack();
                return $this->jumpUrl("系统错误，请联系管理员");
            }
        }else{
            return $this->jumpUrl("请输入床号，下次服务时间，备注等");
        }

    }
    /**
     * 删除服务记录
     * @param $id
     * @param $token
     */
    public function customDel($id ,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }

        $num1    =   DB::table("custom")->where("id","=",$id)->update(array("status"=>0));
        $num2    =   DB::table("notepad")->where("custom_id",'=',$id)->update(array("status"=>0));
        if ($num1 > -1 && $num2 > -1){
            return ajaxReturn([],"删除成功",0);
        }else{
            return ajaxReturn([],'删除失败',1);
        }
    }
    /**
     * 院外回访记录列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customReturnList(){
        if (session("user_id_login") != 1){
            $array['custom.admin_id']  =   session("user_id_login");
            $array['custom.status']    =   1;
        }
        $array['type']  =   0;
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where(data2where($array))->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view("home.custom.return",$view);
    }
    /**
     * 院外回访添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customReturnAdd(){
        $view['department'] =   $this->getDepartment();
        $view['channel']    =   $this->getChannel();
        $view['class']  =   $this->getClass();
        return view("home.custom.returnadd",$view);
    }
    /**
     * 院外回访添加结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customReturnAddResult(Request $request){
        $data   =   $request->input();
        if ($data['user_id'] && $data['username']){
            $where['id']    =   $data['user_id'];
            $where['username']  =   $data['username'];
            $num = DB::table("user")->where(data2where($where))->count("id");
            if (!$num){return $this->jumpUrl("验证失败，请不要随意修改网站内容");}
            DB::table("user")->where(data2where($where))->update(['class_id'=>$data['class_id']]);
            $user['user_id']    =   $data['user_id'];
            $user['username']   =   $data['username'];
        }else{
            $end    =   createtabledata('user',$data);
            if (!$end){return $this->jumpUrl('没有任何数据，非法提交');}
            $end['addtime'] =   time();
            $end['time_id'] =   date("Ymd",time());
            $end['remarks'] =   "出院回访服务添加用户";
            $end['admin_id']    =   session("user_id_login");
            $num    =   DB::table('user')->insertGetId($end);
            if ($num){
                $user['user_id']    =   $num;
                $user['username']   =   $data['username'];
            }else{
                return $this->jumpUrl("用户添加失败？，请联系管理员");
            }
        }
        if (empty($user)){
            return $this->jumpUrl("系统错误，请联系管理员");
        }
        if($endCustom  =   createtabledata("custom",$data)){
            $endCustom['username']  =   $user['username'];
            $endCustom['user_id']   =   $user['user_id'];
            $endCustom['admin_id']  =   session("user_id_login");
            $endCustom['addtime']   =   time();
            $endCustom['type']   =   0;
            $endCustom['bednumber']   =   10240000;
            $time   =   explode("-",$data['nexttime']);
            $endCustom['isin']  =   0;
            $endCustom['question_one']  =   "院外回访无问题";
            $endCustom['question_two']  =   "院外回访无问题";
            $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
            $time   =   explode("-",$data['intime']);
            $endCustom['intime'] =   $this->timeString($time[0],$time[1],$time[2]);
            $time   =   explode("-",$data['outtime']);
            $endCustom['outtime'] =   $this->timeString($time[0],$time[1],$time[2]);
            $endCustom['remarks']   =   time()."|||".$data['lastremarks']."|||".$endCustom['nexttime'];
            $endNotepad['fromid']   =   0;
            $endNotepad['toid']     =   session("user_id_login");
            $endNotepad['message']  =   "您预约了".$data['nexttime']."的出院首次回访服务 -》 ".$user['username'];
            $endNotepad['addtime']  =   time();
            $endNotepad['runtime']  =   $endCustom['nexttime'];
            DB::beginTransaction();
            $num1 = DB::table("custom")->insertGetId($endCustom);
            $endNotepad['custom_id']    =   $num1;
            $num2 = DB::table("notepad")->insert($endNotepad);
            if ($num1 && $num2){
                DB::commit();
                return $this->jumpUrl("操作成功，请时刻关注您的消息栏,消息会在提前一天给您传递");
            }else{
                DB::rollBack();
                return $this->jumpUrl("系统错误，请联系管理员");
            }
        }else{
            return $this->jumpUrl("请输入床号，下次服务时间，备注等");
        }

    }
    /**
     * 服务列表查找
     * @param Request $request
     */
    public function customSearch(Request $request){
        $data   =   $request->input();
        $k    =   $data['name'];
        $v  =   $data['value'];
        session(['search_custom_value'=>$v]);
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
            case 'bednumber':
                return ajaxReturn(['url'=>'bednumber'],'请求成功',0);
                break;
            case 'hisnumber':
                return ajaxReturn(['url'=>'hisnumber'],'请求成功',0);
                break;
            default :
                return ajaxReturn([],'错误的请求方式',1);
        }
    }
    /**
     * 查询服务列表信息 根据用户名
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByUsername($type){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.username','like',"%".$value."%"],['custom.type','=',$type],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view('home.custom.index',$view);
        }else{
            return view('home.custom.return',$view);
        }
    }

    /**
     * @intention 根据床号/病房号查询
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @functionName getCustomListByBedNumber
     * @creatTime 2019/10/26-11:34
     */
    public function getCustomListByBedNumber($type){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.bednumber','=',$value],['custom.type','=',$type],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view('home.custom.index',$view);
        }else{
            return view('home.custom.return',$view);
        }
    }

    /**
     * @intention 根据住院号查询
     * @functionName getCustomListByHisNumber
     * @creatTime 2019/10/26-11:34
     */
    public function getCustomListByHisNumber($type){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.hisnumber','=',$value],['custom.type','=',$type],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view('home.custom.index',$view);
        }else{
            return view('home.custom.return',$view);
        }
    }
    /**
     * 根据等级查询住院服务列表
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByClass($type,$id){
        if (session("user_id_login") != 1){
            $array['custom.admin_id']  =   session("user_id_login");
            $array['custom.status']    =   1;
        }
        $array['custom.type']  =   $type;
        $array['custom.class_id']  =   $id;
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where(data2where($array))->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view("home.custom.index",$view);
        }else{
            return view("home.custom.return",$view);
        }
    }
    /**
     * 查询服务列表信息 根据添加时间
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByTime($type){
        $value  =   session('search_custom_value');
        $time   =   explode("-",$value);
        $time1  =   $this->timeString($time[0],$time[1],$time[2]);
        $time2  =   $this->timeString($time[3],$time[4],$time[5]);
        if (session("user_id_login") != 1){
            $where  =   array(
                'custom.type'=>$type,
                'custom.admin_id'=>session("user_id_login"),
                'custom.status'=>1,
            );
        }else{
            $where  =   array(
                'custom.type'=>$type,
                'custom.status'=>1,
            );
        }
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where(data2where($where))->whereBetween('custom.addtime',[$time1,$time2])->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view('home.custom.index',$view);
        }else{
            return view('home.custom.return',$view);
        }
    }

    /**
     * 查询服务列表信息 根据联系电话
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByTel($type){
        $value  =   session('search_custom_value');
        $userList = DB::table("user")->select("id")->where("tel","=",$value)->get();
        $userListA = array();
        foreach ($userList as $values){
            array_push($userListA,$values->id);
        }
        if (session("user_id_login") != 1){
            $where  =   array(
                'custom.type'=>$type,
                'custom.admin_id'=>session("user_id_login"),
                'custom.status'=>1,
            );
        }else{
            $where  =   array(
                'custom.type'=>$type,
                'custom.status'=>1,
            );
        }
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where(data2where($where))->whereIn("custom.user_id",$userListA)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        if ($type){
            return view('home.custom.index',$view);
        }else{
            return view('home.custom.return',$view);
        }
    }
    /**
     * 添加多次服务信息
     * @param $type
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function addCustomMessage($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $view['id'] =   $id;
        return view('home.custom.addmessage',$view);
    }
    /**
     * 添加多次回访记录
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCustomMessageResult(Request $request){
        $data   =   $request->input();
        if (!$data['nexttime'] && !$data['lastremarks']){
            return $this->jumpUrl("没有填写任何数据");
        }
        $id =   $data['id'];
        DB::beginTransaction();
        DB::table('notepad')->where("custom_id","=",$id)->update(['isrun'=>1,'isread'=>1]);
        $custom     =   DB::table('custom')->find($id);
        $time   =   explode("-",$data['nexttime']);
        $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
        $save   =   array(
            'remarks'   =>  $custom->remarks ."{|}".time()."|||".$data['lastremarks']."|||".$endCustom['nexttime'],
            'lastremarks' =>    $data['lastremarks']
        );
        $number =   DB::table('custom')->where("id","=",$id)->update($save);
        $time   =   explode("-",$data['nexttime']);
        $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
        $endNotepad['fromid']   =   0;
        $endNotepad['toid']     =   session("user_id_login");
        $endNotepad['message']  =   "您预约了".$data['nexttime']."的回访服务==>".$custom->username;
        $endNotepad['addtime']  =   time();
        $endNotepad['runtime']  =   $endCustom['nexttime'];
        $endNotepad['custom_id']    =   $id;
        $num2 = DB::table("notepad")->insert($endNotepad);
        if ($num2 && $number){
            DB::commit();
            return $this->show("操作成功，请时刻关注您的消息栏,消息会在提前一天给您传递");
        }else{
            DB::rollBack();
            return $this->jumpUrl("操作失败，请重试");
        }
    }
    /**
     * 加载服务详细信息页面数据
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function showCustomMessage($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $message    =   DB::table('custom')->find($id);
        $remarks    =   $message->remarks;
        $remarks    =   explode("{|}",$remarks);
        if (is_array($remarks)){
            foreach ($remarks as $value){
                $onp    =   explode("|||",$value);
                $lastremarks[]  =   array(
                    'addtime'=>isset($onp[0])?$onp[0]:null,
                    'remarks'=>isset($onp[1])?$onp[1]:null,
                    'nexttime'=>isset($onp[2])?$onp[2]:null
                );
            }
        }
        $view['remarks']    =   $lastremarks;
        $view['custom']     =   $message;
        return view("home.custom.shownote",$view);
    }
    /**
     * 出院按钮
     * @param $id
     * @param $token
     */
    public function leaveHospital(Request $request,$id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $save['type']   =   0;
        $save['outtime']    =$request->input('outtime');
        $time   =   explode("-",$save['outtime']);
        $save['outtime'] =   $this->timeString($time[0],$time[1],$time[2]);
        $number =   DB::table('custom')->where("id","=",$id)->update($save);
        if ($number){
            return ajaxReturn([],'操作成功',0);
        }else{
            return ajaxReturn([],'操作失败',1);
        }
    }
    /**
     * 加载客服预约用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAppointmentUser(){
        $userid =   session("user_id_login");
        $where['admin_id']  =   $userid;
        $where['status']    =   1;
        if ($userid == 1){
            $view['user']   =   DB::table('user_custom')->select('id','username','tel','addtime','cometime','user_id')->paginate(15);
        }else{
            $view['user']  =   DB::table('user_custom')->where(data2where($where))->select('id','username','tel','addtime','cometime','user_id')->paginate(15);
        }
        return view('home.custom.appointment',$view);
    }
    /**
     * 客服预约添加用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAppointmentUserAdd(){
        $view['department'] =   $this->getDepartment();
        return view("home.custom.addappoint",$view);
    }
    /**
     * 客服预约用户添加结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAppointmentUserAddResult(Request $request){
        $data   =   $request->input();
        $end    =   createtabledata('user_custom',$data);
        if (!$end){return $this->jumpUrl('没有任何数据，非法提交');}
        $where['username']  =   $end['username'];
        $where['tel']   =   $end['tel'];
        $where['status']    =   1;
        $number =   DB::table('user_custom')->where(data2where($where))->first();
        if (!empty($number)){
            return $this->jumpUrl('已存在你要添加的记录当中');
        }else{
            $end['addtime'] =   time();
            $end['time_id'] =   date("Ymd",time());
            $end['admin_id']    =   session('user_id_login');
            $num    =   DB::table('user_custom')->insert($end);
            if ($num){
                return $this->jumpUrl('添加成功',url('/home/page/custom/appointment'));
            }else{
                return $this->jumpUrl('添加失败');
            }
        }
    }
    /**
     * 删除单个预约记录
     * @param $id
     * @param $token
     */
    public function customAppointmentUserDel($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $num1    =   DB::table("user_custom")->where("id","=",$id)->update(array("status"=>0));
        if ($num1 > -1){
            return ajaxReturn([],"删除成功",0);
        }else{
            return ajaxReturn([],'删除失败',1);
        }
    }
    /**
     * 等级分配中选择服务
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCustomListMessage(){
        $view['list']   =   DB::table("custom")->where('class_id','=',0)->select('id','user_id','username','addtime','class_id')->paginate(15);
        return view('home.custom.custom',$view);
    }
    /**
     * 查询预约用户
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customAppointmentSearch(Request $request){
        $value  =   session('search_user_value');
        switch ($request->input('type')){
            case 'time':
                $time   =   explode("-",$value);
                $time1  =   $this->timeString($time[0],$time[1],$time[2]);
                $time2  =   $this->timeString($time[3],$time[4],$time[5]);
                if (session('user_id_login') == 1){
                    $view['user']   =   DB::table('user_custom')->select('id','username','tel','addtime','cometime','user_id')->whereBetween('addtime',[$time1,$time2])->paginate(15);
                }else{
                    $where['admin_id']  =   session('user_id_login');
                    $view['user']  =   DB::table('user_custom')->where(data2where($where))->select('id','username','tel','addtime','cometime','user_id')->whereBetween('addtime',[$time1,$time2])->paginate(15);
                }
                break;
            default :
                if (session('user_id_login') == 1){
                    $where[$request->input('type')]  =   $value;
                    $view['user']   =   DB::table('user_custom')->select('id','username','tel','addtime','cometime','user_id')->where(data2where($where))->paginate(15);
                }else{
                    $where[$request->input('type')]  =   $value;
                    $where['admin_id']  =   session('user_id_login');
                    $view['user']  =   DB::table('user_custom')->where(data2where($where))->select('id','username','tel','addtime','cometime','user_id')->paginate(15);
                }
        }
        return view('home.custom.appointment',$view);
    }
    /**
     * 修改最后一条备注信息
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function editCustomMessage($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $view['id'] =   $id;
        $custom =   DB::table('custom')->select('remarks')->find($id);
        $remarks    =   explode("{|}",$custom->remarks);
        if (is_array($remarks)){
                $value  =   $remarks[count($remarks)-1];
                $onp    =   explode("|||",$value);
                $lastremarks  =   array(
                    'addtime'=>isset($onp[0])?$onp[0]:null,
                    'remarks'=>isset($onp[1])?$onp[1]:null,
                    'nexttime'=>isset($onp[2])?$onp[2]:null
                );
        }
        $view['remarks']    =   $lastremarks;
        return view('home.custom.editmessage',$view);
    }
    /**
     * 修改最后一条备注信息结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCustomMessageResult(Request $request){
        $data   =   $request->input();
        if (!$data['nexttime'] && !$data['lastremarks']){
            return $this->jumpUrl("没有填写任何数据");
        }
        $id =   $data['id'];
        DB::beginTransaction();
        DB::table('notepad')->where("custom_id","=",$id)->update(['isrun'=>1,'isread'=>1,'isedit'=>1]);
        $custom     =   DB::table('custom')->find($id);
        $time   =   explode("-",$data['nexttime']);
        $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
        $remarks    =   $custom->remarks;
        $remarks    =   explode("{|}",$remarks);
        $lastremarks    =   "";
        if (is_array($remarks)){
            unset($remarks[count($remarks)-1]);
            foreach ($remarks as $value){
                $onp    =   explode("|||",$value);
                $addtime    =   isset($onp[0])?$onp[0]:0;
                $remarks    =  isset($onp[1])?$onp[1]:'无';
                $nexttime   =   isset($onp[2])?$onp[2]:0;
                if ($lastremarks){
                    $lastremarks    .=  "{|}".$addtime."|||".$remarks."|||".$nexttime;
                }else{
                    $lastremarks    =   $addtime."|||".$remarks."|||".$nexttime;
                }
            }
        }
        if ($lastremarks){
            $lastremarks    .=  "{|}".time()."|||".$data['lastremarks']."|||".$endCustom['nexttime'];
        }else{
            $lastremarks    =   $data['addtime']."|||".$data['lastremarks']."|||".$endCustom['nexttime'];
        }
        $save   =   array(
            'remarks'   =>      $lastremarks,
            'lastremarks' =>    $data['lastremarks']
        );
        $number =   DB::table('custom')->where("id","=",$id)->update($save);
        $time   =   explode("-",$data['nexttime']);
        $endCustom['nexttime'] =   $this->timeString($time[0],$time[1],$time[2]);
        $endNotepad['fromid']   =   0;
        $endNotepad['toid']     =   session("user_id_login");
        $endNotepad['message']  =   "您预约了".$data['nexttime']."的回访服务==>".$custom->username;
        $endNotepad['addtime']  =   time();
        $endNotepad['runtime']  =   $endCustom['nexttime'];
        $endNotepad['custom_id']    =   $id;
        $num2 = DB::table("notepad")->insert($endNotepad);
        if ($num2 && $number){
            DB::commit();
            return $this->show("操作成功，请时刻关注您的消息栏,消息会在提前一天给您传递");
        }else{
            DB::rollBack();
            return $this->jumpUrl("操作失败，请重试");
        }
    }
    /**
     * 查询服务列表信息 根据用户名
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByUsernameOutTime(){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.username','like',"%".$value."%"],['custom.outtime','>',0],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view('home.custom.index',$view);
    }

    /**
     * @intention 根据床号/病房号查询
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @functionName getCustomListByBedNumber
     * @creatTime 2019/10/26-11:34
     */
    public function getCustomListByBedNumberOutTime(){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.bednumber','like',"%".$value."%"],['custom.outtime','>',0],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view('home.custom.index',$view);
    }

    /**
     * @intention 根据住院号查询
     * @functionName getCustomListByHisNumber
     * @creatTime 2019/10/26-11:34
     */
    public function getCustomListByHisNumberOutTime($type){
        $value  =   session('search_custom_value');
        $where  =   array(
            ['custom.hisnumber','=',$value],['custom.outtime','>',0],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
        );
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view('home.custom.index',$view);
    }
    /**
     * 查询服务列表信息 根据添加时间
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByTimeOutTime(){
        $value  =   session('search_custom_value');
        $time   =   explode("-",$value);
        $time1  =   $this->timeString($time[0],$time[1],$time[2]);
        $time2  =   $this->timeString($time[3],$time[4],$time[5]);
        if (session("user_id_login") != 1){
            $where  =   array(
                ['custom.outtime','>',0],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
            );
        }else{
            $where  =   array(
                ['custom.outtime','>',0],['custom.status','=',1]
            );
        }
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->whereBetween('custom.addtime',[$time1,$time2])->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view('home.custom.index',$view);
    }

    /**
     * 查询服务列表信息 根据联系电话
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomListByTelOutTime(){
        $value  =   session('search_custom_value');
        $userList = DB::table("user")->select("id")->where("tel","=",$value)->get();
        $userListA = array();
        foreach ($userList as $values){
            array_push($userListA,$values->id);
        }
        if (session("user_id_login") != 1){
            $where  =   array(
                ['custom.type','=',0],['custom.outtime','>',0],['custom.admin_id','=',session('user_id_login')],['custom.status','=',1]
            );
        }else{
            $where  =   array(
                ['custom.type','=',0],['custom.outtime','>',0],['custom.status','=',1]
            );
        }
        $view['list']  =   DB::table('custom')->select('custom.id as custom_id','custom.intime','custom.status as custom_status','custom.outtime','custom.lastremarks','user.*')->where($where)->whereIn("custom.user_id",$userListA)->leftJoin('user','custom.user_id','=','user.id')->orderBy("custom.id","desc")->paginate(15);
        $view['class']  =   $this->getClass();
        return view('home.custom.index',$view);
    }
}