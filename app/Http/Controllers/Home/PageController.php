<?php

namespace App\Http\Controllers\Home;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index(){
        $view['menu']   =   $this->userRoleById();
        return view("home.page.index",$view);
    }
    /**
     * webinfo
     */
    public function webInfo(){
        $role   =   $this->userRoleById();
    }
    /**
     * 清除缓存
     */
    public function clearCache(){
        Cache::add('key','value','1');
        Cache::flush();
        if (Cache::has('key')){
            return ajaxReturn([],'清除缓存失败，请联系管理员',1);
        }else{
            return ajaxReturn([],'清除成功',0);
        }
    }
    /**
     * 加载展示就诊科室显示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function department(){
        $view['list']   =   DB::table('department')->get();
        return view("home.page.department",$view);
    }
    /**
     * 加载来诊渠道显示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function channel(){
        $view['list']   =   DB::table('channel')->get();
        return view("home.page.channel",$view);
    }
    /**
     * 加载就诊科室添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addDepartment(){
        return view("home.page.adddepartment");
    }
    /**
     * 添加就诊科室过程和结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addDepartmentResult(Request $request){
        $data   =   $request->input();
        $name   =   $data['name'];
        $message    =   DB::table('department')->where("name","=",$name)->first();
        if (empty($message)){
            $end['name']    =   $data['name'];
            $end['pid']     =   0;
            $end['status']  =   1;
            $number =   DB::table('department')->insertGetId($end);
            if ($number){
                return $this->jumpUrl("添加成功");
            }else{
                return $this->jumpUrl("添加失败，请联系管理员操作");
            }
        }else{
            return $this->jumpUrl("该名称已存在");
        }
    }
    /**
     * 加载就诊科室修改页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDepartment($id){
        $view['department'] =   DB::table('department')->find($id);
        return view("home.page.editdepartment",$view);
    }
    /**
     * 修改就诊科室过程和结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDepartmentResult(Request $request){
        $data   =   $request->input();
        $name   =   $data['name'];
        $message    =   DB::table('department')->where("name","=",$name)->first();
        if (empty($message)){
            $end['name']    =   $data['name'];
            $number =   DB::table('department')->where("id","=",$data['id'])->update($end);
            if ($number){
                return $this->jumpUrl("修改成功");
            }else{
                return $this->jumpUrl("修改失败，请联系管理员操作");
            }
        }else{
            return $this->jumpUrl("该名称已存在");
        }
    }
    /**
     * 删除就诊科室
     * @param $id
     * @param $token
     */
    public function delDepartment(Request $request,$id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $user =   DB::table('user')->where('department_id',"=",$id)->count('id');
        if ($user){
            return ajaxReturn([],'操作失败，该科室下还有人员，请先合并后再操作',1);
        }
        $status =   $request->get('type') == 1? 0 : 1;
        $number =   DB::table('department')->where("id","=",$id)->update(['status'=>$status]);
        if ($number > -1){
            Cache::flush();
            return ajaxReturn([],'操作成功！',0);
        }else{
            return ajaxReturn([],'操作失败，数据出错了',1);
        }
    }
    /**
     * 加载合并就诊科室页面
     */
    public function mergeDepartment(){
        $view['list']   =   DB::table('department')->get();
        return view('home.page.mergedepartment',$view);
    }
    /**
     * 合并就诊科室结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mergeDepartmentResult(Request $request){
        $data   =   $request->post('department_id');
        $end['department_id']    =   $request->post('mergedepartment_id');
        if (!$end['department_id']){return $this->jumpUrl('请选择合并后的名称');}
        if (count($data) <= 1){return $this->jumpUrl('最少选择两个合并项');}
        $number =   DB::table('user')->whereIn('department_id',$data)->update($end);
        if ($number > -1){
            return $this->jumpUrl('操作成功');
        }else{
            return $this->jumpUrl('操作失败，请联系管理员修改');
        }
    }
    /**
     * 加载添加来院渠道页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addChannel(){
        return view("home.page.addchannel");
    }
    /**
     * 添加来源渠道过程和结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addChannelResult(Request $request){
        $data   =   $request->input();
        $name   =   $data['name'];
        $message    =   DB::table('channel')->where("name","=",$name)->first();
        if (empty($message)){
            $end['name']    =   $data['name'];
            $end['pid']     =   0;
            $end['status']  =   1;
            $number =   DB::table('channel')->insertGetId($end);
            if ($number){
                return $this->jumpUrl("添加成功");
            }else{
                return $this->jumpUrl("添加失败，请联系管理员操作");
            }
        }else{
            return $this->jumpUrl("该名称已存在");
        }
    }
    /**
     * 加载来院渠道修改页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editChannel($id){
        $view['channel'] =   DB::table('channel')->find($id);
        return view("home.page.editchannel",$view);
    }
    /**
     * 修改来院渠道过程和结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editChannelResult(Request $request){
        $data   =   $request->input();
        $name   =   $data['name'];
        $message    =   DB::table('channel')->where("name","=",$name)->first();
        if (empty($message)){
            $end['name']    =   $data['name'];
            $number =   DB::table('channel')->where("id","=",$data['id'])->update($end);
            if ($number){
                return $this->jumpUrl("修改成功");
            }else{
                return $this->jumpUrl("修改失败，请联系管理员操作");
            }
        }else{
            return $this->jumpUrl("该名称已存在");
        }
    }
    /**
     * 删除就诊科室
     * @param $id
     * @param $token
     */
    public function delChannel(Request $request,$id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $user =   DB::table('user')->where('channel_id',"=",$id)->count('id');
        if ($user){
            return ajaxReturn([],'操作失败，该渠道下还有人员，请先合并后再操作',1);
        }
        $status =   $request->get('type') == 1? 0 : 1;
        $number =   DB::table('channel')->where("id","=",$id)->update(['status'=>$status]);
        if ($number > -1){
            Cache::flush();
            return ajaxReturn([],'操作成功！',0);
        }else{
            return ajaxReturn([],'操作失败，数据出错了',1);
        }
    }
    /**
     * 加载合并来诊渠道页面
     */
    public function mergeChannel(){
        $view['list']   =   DB::table('channel')->get();
        return view('home.page.mergechannel',$view);
    }
    /**
     * 合并来诊渠道结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mergeChannelResult(Request $request){
        $data   =   $request->post('channel_id');
        $end['channel_id']    =   $request->post('mergechannel_id');
        if (!$end['channel_id']){return $this->jumpUrl('请选择合并后的名称');}
        if (count($data) <= 1){return $this->jumpUrl('最少选择两个合并项');}
        $number =   DB::table('user')->whereIn('channel_id',$data)->update($end);
        if ($number > -1){
            return $this->jumpUrl('操作成功');
        }else{
            return $this->jumpUrl('操作失败，请联系管理员修改');
        }
    }
    /**
     * 加载右下角消息队列
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notepadList(){
        $toid   =   session("user_id_login");
        $view['note']   =   DB::table("notepad")->where("toid","=",$toid)->orWhere("toid","=",-1)->orderBy("addtime","desc")->paginate(5);
        return view("home.page.note",$view);
    }
    /**
     * 消息内容执行
     * @param $id
     * @param $token
     */
    public function notepadRun($id ,$token){
        $num    =   DB::table('notepad')->where("id","=",$id)->update(['isrun'=>1]);
        if ($num){
            return ajaxReturn([],'操作成功',0);
        }else{
            return ajaxReturn([],'操作失败',1);
        }
    }
    /**
     * 消息内容阅读
     * @param $id
     * @param $token
     */
    public function notepadRead($id ,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $num    =   DB::table('notepad')->where("id","=",$id)->update(['isread'=>1,'isrun'=>1]);
        if ($num){
            return ajaxReturn([],'操作成功',0);
        }else{
            return ajaxReturn([],'操作失败',1);
        }
    }
    /**
     * 加载消息列表中，消息的全部内容
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notepadReadMessage($id ,$token){
        $note_message   =   DB::table('notepad')->find($id);
        $custom_id  =   $note_message->custom_id;
        $note_message   =   DB::table('notepad')->where('custom_id','=',$custom_id)->get();
        $custom_message =   DB::table('custom')->find($custom_id);
        $user_message   =   DB::table('user')->find($custom_message->user_id);
        $remarks    =   $custom_message->remarks;
        $remarks    =   explode("{|}",$remarks);
        if (is_array($remarks)){
            foreach ($remarks as $value){
                $onp    =   explode("|||",$value);
                $lastremarks[]  =   array(
                    'addtime'=>isset($onp[0])?(int)$onp[0]:null,
                    'remarks'=>isset($onp[1])?$onp[1]:null,
                    'nexttime'=>isset($onp[2])?(int)$onp[2]:null
                );
            }
        }
        $view['remarks']    =   $lastremarks;
        $view['users']  =   $user_message;
        $view['users']->channel   =   $this->getChannelNameByIdOne($user_message->channel_id);
        $view['users']->department    =   $this->getDepartmentNameByIdOne($user_message->department_id);
        $view['users']->admin =   $this->getAdminNameById($user_message->admin_id);
        $view['custom'] =   $custom_message;
        $view['custom']->admin   =   $this->getAdminNameById($custom_message->admin_id);
        $view['notepad']    =   $note_message;
        return view('home.page.notemessage',$view);
    }
    /**
     * 加载等级列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classList(){
        $view['class']   =   DB::table('class')->get();
        return view('home.page.class',$view);
    }
    /**
     * 加载添加等级页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classAdd(){
        return view('home.page.addclass');
    }
    /**
     * 添加等级结果及显示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classAddResult(Request $request){
        $data   =   $request->input();
        $end    =   createtabledata('class',$data);
        if (!$end){return $this->jumpUrl('没有输入任何内容，请填写内容后重试');}
        $message    =   DB::table('class')->where("name","=",$end['name'])->first();
        if (!empty($message)){return $this->jumpUrl('已存在该名称，请更换名称重试');}
        $end['status']  =   1;
        $number =   DB::table('class')->insertGetId($end);
        if ($number){
            Cache::flush();
            return $this->jumpUrl('添加成功');
        }else{
            return $this->jumpUrl('出现错误了，请联系管理员进行操作');
        }
    }
    /**
     * 加载等级修改页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classEdit($id){
        $view['class'] =   DB::table('class')->find($id);
        return view('home.page.editclass',$view);
    }
    /**
     * 等级修改结果及显示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classEditResult(Request $request){
        $data   =   $request->input();
        $end    =   createtabledata('class',$data);
        if (!$end){return $this->jumpUrl('没有输入任何内容，请填写内容后重试');}
        $message    =   DB::table('class')->where("name","=",$end['name'])->first();
        if (!empty($message)){return $this->jumpUrl('已存在该名称，请更换名称重试');}
        $end['status']  =   1;
        $number =   DB::table('class')->where("id",'=',$data['id'])->update($end);
        if ($number){
            Cache::flush();
            return $this->jumpUrl('修改成功');
        }else{
            return $this->jumpUrl('出现错误了，请联系管理员进行操作');
        }
    }
    /**
     * 显示及隐藏等级
     * @param Request $request
     * @param $id
     * @param $token
     */
    public function classDel(Request $request,$id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $user =   DB::table('custom')->where('class_id',"=",$id)->count('id');
        if ($user){
            return ajaxReturn([],'操作失败，该等级下还有服务列表内容，请先合并后再操作',1);
        }
        $status =   $request->get('type') == 1? 0 : 1;
        $number =   DB::table('class')->where("id","=",$id)->update(['status'=>$status]);
        if ($number > -1){
            Cache::flush();
            return ajaxReturn([],'操作成功！',0);
        }else{
            return ajaxReturn([],'操作失败，数据出错了',1);
        }
    }
    /**
     * 加载合并多等级页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classMerge(){
        $view['list']   =   DB::table('class')->get();
        return view('home.page.mergeclass',$view);
    }
    /**
     * 合并多个等级结果和显示
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classMergeResult(Request $request){
        $data   =   $request->post('class_id');
        $end['class_id']    =   $request->post('mergeclass_id');
        if (!$end['class_id']){return $this->jumpUrl('请选择合并后的名称');}
        if (count($data) <= 1){return $this->jumpUrl('最少选择两个合并项');}
        $number =   DB::table('custom')->whereIn('class_id',$data)->update($end);
        if ($number > -1){
            return $this->jumpUrl('操作成功');
        }else{
            return $this->jumpUrl('操作失败，请联系管理员修改');
        }
    }
    /**
     * 验证码
     * @param int $length
     */
    public function captcha(int $length){
        $builder    =   new CaptchaBuilder($length);
        $phrase = $builder->getPhrase();
        $builder->build($width = 100, $height = 32, $font = null);
        //把内容存入session
        session(['captcha'=>$phrase]);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
    /**
     * 加载等级分配页面
     */
    public function classDistribution(){
        $view['class']  =   $this->getClass();
        return view('home.page.distribution',$view);
    }
    /**
     * 等级分类操作
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function classDistributionResults(Request $request){
        $data   =   $request->input();
        if (!array_key_exists('class_id',$data)){return $this->jumpUrl('请选择等级');}
        if (!$data['class_id']){return $this->jumpUrl('请选择等级');}
        if (array_key_exists('user_id',$data)){
            if (empty($data['user_id'])){return $this->jumpUrl('请选择用户');}else{
                DB::table('user')->whereIn('id',$data['user_id'])->update(['class_id'=>$data['class_id']]);
            }
        }
        if (array_key_exists('custom_id',$data)){
            if (empty($data['custom_id'])){return $this->jumpUrl('请选择服务条目');}else{
                DB::table('custom')->whereIn('id',$data['custom_id'])->update(['class_id'=>$data['class_id']]);
            }
        }
        return $this->jumpUrl('操作成功');
    }

    /**
     * 根据类型加载列表
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notepadListByType($type){
        $gettime    =   getTime();
        $toid   =   session("user_id_login");
        if ($type == 0){
            $start  =   mktime(0,0,0,$gettime['m'],$gettime['d'],$gettime['y']);
            $end  =   mktime(23,59,59,$gettime['m'],$gettime['d'],$gettime['y']);
            $sql    =   "SELECT * FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid} and runtime BETWEEN {$start} and {$end} order by runtime desc";
        }else if($type == -1){
            $start  =   mktime(0,0,0,$gettime['m'],$gettime['d']+1,$gettime['y']);
            $end  =   mktime(23,59,59,$gettime['m'],$gettime['d']+1,$gettime['y']);
            $sql    =   "SELECT * FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid} and runtime BETWEEN {$start} and {$end} order by runtime desc";
        }else{
            $sql    =   "SELECT * FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid} order by runtime desc";
        }
        $view['note']   =   DB::select($sql);
        return view("home.page.note",$view);
    }
}
