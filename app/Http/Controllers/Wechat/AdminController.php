<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * 加载微信后台管理系统
     * @param $wechat_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($wechat_id){
        $wechat_id = scafelEncrypt($wechat_id,"D");
        session(['wechat_id'=>$wechat_id]);
        $wechat =   DB::table('wechat')->where("id",'=',$wechat_id)->first();
        foreach ($wechat as $key=>$value){
            if ($key == "type"){}else{
                if (!$value){
                    return $this->jumpUrl("请先完善公众号配置，再进行内容维护");
                }
            }
        }
        return view("wechat.admin.index");
    }
    public function gamesList(){
        $view['games']  =   DB::table("wechat_games")->simplePaginate(15);
        return view("admin.wechat.games",$view);
    }
    public function gamesAdd(){
        return view("admin.wechat.gamesadd");
    }
    public function gamesAddResult(){

    }
    public function gamesEdit(){

    }
    public function gamesEditResult(){

    }
    public function gamesDel(){

    }
    /**
     * 加载关键词列表
     * @param $wechat_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keyWordList($wechat_id){
        $view['keywords']   =   DB::table('wechat_keywords')->where("wechat_id","=",scafelEncrypt($wechat_id,"D"))->simplePaginate();
        $view['count']  =   count($view['keywords']);
        return view("admin.wechat.keywords",$view);
    }
    /**
     * 加载关键词添加页面
     * @param $wechat_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keyWordAdd($wechat_id){
        $view['wechat_id']  =   $wechat_id;
        return view("admin.wechat.keywordsadd",$view);
    }
    /**
     * 关键词添加结果
     * @param Request $request
     * @param $wechat_id
     */
    public function keyWordAddResult(Request $request,$wechat_id){
        $data   =   $request->input();
        $end    =   createtabledata("wechat_keywords",$data);
        $end['addtime'] =   time();
        $end['status']  =   1;
        $end['wechat_id']   =   scafelEncrypt($wechat_id,"D");
        $num    =   DB::table("wechat_keywords")->insertGetId($end);
        if ($num){
            return $this->jumpUrl("添加成功",url("wechat/$wechat_id/admin/keywords"));
        }else{
            return $this->jumpUrl("添加失败");
        }
    }
    /**
     * 加载关键词修改页面
     * @param $wechat_id
     * @param $id
     * @param $token
     */
    public function keyWordEdit($wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){
            return ajaxReturn([],"验证失败",1);
        }
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['id']    =   $id;
        $view['keywords'] =   DB::table("wechat_keywords")->where(data2where($where))->first();
        return view("admin.wechat.keywordsedit",$view);
    }
    /**
     * 关键词修改结果
     * @param Request $request
     * @param $wechat_id
     */
    public function keyWordEditResult(Request $request,$wechat_id){
        $data   =   $request->input();
        if (!idMd5Token($data['id'],$data['idtoken'])){
            return ajaxReturn([],"验证失败",1);
        }
        DB::beginTransaction();
        $numDel   =   DB::table("wechat_keywords")->delete($data['id']);
        if ($numDel > -1){}else{DB::rollBack();return ajaxReturn([],"修改失败",1);}
        $end    =   createtabledata("wechat_keywords",$data);
        $end['addtime'] =   time();
        $end['status']  =   1;
        $end['wechat_id']   =   scafelEncrypt($wechat_id,"D");
        $num    =   DB::table("wechat_keywords")->insertGetId($end);
        if ($num){
            DB::commit();
            return ajaxReturn([],'添加成功',0);
        }else{
            DB::rollBack();
            return ajaxReturn([],'添加失败',1);
        }
    }
    /**
     * 删除关键词
     * @param $id
     * @param $token
     */
    public function keyWordDel($id,$token){
        if (!idMd5Token($id,$token)){
            return ajaxReturn([],"验证失败",1);
        }
        $num    =   DB::table("wechat_keywords")->where("id","=",$id)->delete();
        if($num > -1){
            return ajaxReturn([],"删除成功",0);
        }else{
            return ajaxReturn([],"删除失败",1);
        }
    }
    /**
     * 文件上传
     * @param Request $request
     * @param $wechat_id
     */
    public function uploadMaterial(Request $request,$wechat_id){
        $upload   =   $request->file();
        if (empty($upload)){
            return ajaxReturn([],'未进行文件上传操作',1);
        }
        foreach ($_FILES as $key=>$value){
            $path = $request->file($key)->store('upload','scafel');
            $data[$key]['url'] =   '/'.$path;
            $uploads =   array(
                "filename"=>'/'.$path,
                "filelength"=>$value['size'],
                "content-type"=>$value['type'],
            );
            $data[$key]['wechat'] =   $this->addMaterial(scafelEncrypt($wechat_id,"D"),$uploads['filename'],"image");
        }
        if (count($data) < 1){
            return ajaxReturn($data,'上传失败',1);
        }else{
            return ajaxReturn($data,'上传成功',0);
        }
    }
    /**
     * 新增永久素材
     * @param $wechat_id
     * @param $data
     * @param $type
     * @return mixed
     */
    public function addMaterial($wechat_id,$data,$type){
        $conf   =   new WechatController($wechat_id);
        $access_token   =   $conf->getAccessTokenP();
        $url    =   "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$type}";
        $material   =   json_decode(httpRequest($url,"POST",[ 'media'=> new \CURLFile(realpath($data))]),true);
        if (isset($material['errcode']) && $material['errcode'] > 0){
            return $material['errmsg'];
        }else{
            return $material;
        }
    }
    /**
     * 加载问卷列表
     * @param $wechat_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionList($wechat_id){
        $view['wechat'] =   $wechat_id;
        $view['list']   =   DB::table("wechat_question")->where("wechat_id","=",scafelEncrypt($wechat_id,"D"))->simplePaginate(15);
        return view("admin.wechat.question",$view);
    }
    /**
     * 加载问卷列表添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAdd($wechat_id){
        return view("admin.wechat.questionadd");
    }
    /**
     * 问卷pot数据提交结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAddResult(Request $request,$wechat_id){
        $data   =   $request->input();
        $json   =   $this->questionAnswerToJson($data);
        if (!$json){return $this->jumpUrl("没有添加题目和答案");}
        $inster =   array(
            "question_id" =>time().rand(0,999),
            "answer"    =>  serialize($json),
            "question_key_word" => "scafel",
            "question_name" =>  $data['question_name'],
            "isshow"=>1,
            "wechat_id"=>scafelEncrypt($wechat_id,"D"),
            "addtime"=>time()
        );
        $num    =   DB::table("wechat_question")->insert($inster);
        if ($num){
            return $this->jumpUrl("添加成功",url("wechat/$wechat_id/admin/question"));
        }else{
            return $this->jumpUrl("添加失败了");
        }
    }
    /**
     * 加载问卷分值列表添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAddCode($wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){
            return $this->jumpUrl("验证失败");
        }
        $view['wechat_id']  =   $wechat_id;
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['question_id']   =   $id;
        $view['question']   =   DB::table("wechat_question")->where(data2where($where))->first();
        $view['codelist']   =   DB::table("wechat_question_code_list")->where(data2where($where))->get();
        return view("admin.wechat.questionaddcode",$view);
    }
    /**
     * 问卷分值post数据提交结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAddCodeResult(Request $request,$wechat_id){
        $data   =   $request->input();
        if (!idMd5Token($data['question_id'],$data['question_token'])){return $this->jumpUrl("校验错误");}
        $where['question_id']   =   $data['question_id'];
        $where['wechat_id']     =   scafelEncrypt($wechat_id,"D");
        DB::beginTransaction();
        $del    =   DB::table("wechat_question_code_list")->where(data2where($where))->delete();
        if ($del > -1){}else{ return $this->jumpUrl("添加失败,请联系网站管理员");}
        $answer =   $data['answer'];
        $range  =   $data['range'];
        $title  =   $data['title'];
        $code   =   $data['code'];
        for ($i = 0 ; $i < count($answer) ;$i++ ){
            $insert[$i]['code'] =   $code[$i]??0;
            $insert[$i]['title'] =   $title[$i]??0;
            $insert[$i]['answer'] =   $answer[$i]??0;
            $insert[$i]['range'] =   $range[$i]??0;
            $insert[$i]['question_id']  =   $data['question_id'];
            $insert[$i]['wechat_id']    =   scafelEncrypt($wechat_id,"D");
        }
        $num    =   DB::table("wechat_question_code_list")->insert($insert);
        if ($num){
            DB::commit();
            return $this->jumpUrl("添加成功",url("wechat/{$wechat_id}/admin/question"));
        }else{
            DB::rollBack();
            return $this->jumpUrl("添加失败,请联系网站管理员");
        }
    }
    /**
     * 加载问卷友情提示列表添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAddTips($wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){return $this->jumpUrl("校验错误");}
        $view['wechat_id']  =   $wechat_id;
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['question_id']   =   $id;
        $view['question']   =   DB::table("wechat_question")->where(data2where($where))->first();
        $view['tips']   =   DB::table("wechat_question_tips")->where(data2where($where))->first();
        return view("admin.wechat.questionaddtips",$view);
    }
    /**
     * 问卷友情提示post数据提交结果
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionAddTipsResult(Request $request,$wechat_id){
        $data   =   $request->input();
        if (!idMd5Token($data['question_id'],$data['question_token'])){return $this->jumpUrl("校验错误");}
        $inster['question_id']   =   $data['question_id'];
        $inster['wechat_id']     =   scafelEncrypt($wechat_id,"D");
        DB::beginTransaction();
        $del    =   DB::table("wechat_question_tips")->where(data2where($inster))->delete();
        if ($del > -1){}else{DB::rollBack();  return $this->jumpUrl("添加失败,请联系网站管理员");}
        $title  =   $data['smalltitle'];
        $answer =   $data['smalltitlemessage'];
        for ($i = 0 ; $i < count($title);$i++){
            $message[$i]["title"] =   $title[$i];
            $message[$i]["message"] =   $answer[$i];
        }
        $inster['message']  =   serialize($message);
        $inster['title']    =   $data['title'];
        $num    =   DB::table("wechat_question_tips")->insert($inster);
        if ($num){
            DB::commit();
            return $this->jumpUrl("添加成功",url("wechat/{$wechat_id}/admin/question"));
        }else{
            DB::rollBack();
            return $this->jumpUrl("添加失败,请联系网站管理员");
        }
    }
    /**
     * 问卷修改加载页面
     * @param Request $request
     * @param $wechat_id
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionEdit(Request $request,$wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){
            return $this->jumpUrl("验证失败");
        }
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['question_id']   =   $id;
        $question   =   DB::table("wechat_question")->where(data2where($where))->first();
        $question->answer = unserialize($question->answer);
        $view['question']   =   $question;
        $view['wechat_id']  =   $wechat_id;
        return view("admin.wechat.questionedit",$view);
    }
    /**
     * 问卷修改post数据提交结果
     * @param Request $request
     * @param $wechat_id
     */
    public function questionEditResult(Request $request,$wechat_id){
        $data   =   $request->input();
        if (!idMd5Token($data['question_id'],$data['question_token'])){
            return $this->jumpUrl("验证失败");
        }
        $json   =   $this->questionAnswerToJson($data);
        if (!$json){return $this->jumpUrl("没有添加题目和答案");}
        $update =   array(
            "answer"    =>  serialize($json),
            "question_key_word" => "scafel",
            "question_name" =>  $data['question_name'],
            "isshow"=>1,
            "addtime"=>time()
        );
        $where  =   array(
            "question_id" =>$data['question_id'],
            "wechat_id"=>scafelEncrypt($wechat_id,"D"),
        );
        $num    =   DB::table("wechat_question")->where(data2where($where))->update($update);
        if ($num > -1){
            return $this->jumpUrl("修改成功",url("wechat/$wechat_id/admin/question"));
        }else{
            return $this->jumpUrl("修改失败");
        }
    }
    /**
     * 加载问卷内容查看
     * @param $wechat_id
     * @param $id
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function questionShow($wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){
            return $this->jumpUrl("验证失败");
        }
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['question_id']   =   $id;
        $question   =   DB::table("wechat_question")->where(data2where($where))->first();
        $question->answer = unserialize($question->answer);
        $view['question']   =   $question;
        return view("admin.wechat.questionshow",$view);
    }
    /**
     * 问题答案转json
     * @param $data
     * @return bool
     */
    public function questionAnswerToJson($data){
        if (isset($data['question_title']) && !empty($data['question_title'])){
            foreach ($data['question_title'] as $key=>$value){
                if ($value){
                    $return[$key]['title']   =   $value;
                    if (isset($data['question_answer'.$key]) && isset($data['question_code'.$key])){
                        for ($i = 0; $i < count($data['question_answer'.$key]);$i++){
                            if ($data['question_answer'.$key][$i]){
                                $return[$key]["child"][$i]['answer']  =    $data['question_answer'.$key][$i];
                                $return[$key]["child"][$i]['code']  =    $data['question_code'.$key][$i]??0;
                            }
                        }
                    }
                }
            }
            return $return;
        }else{
            return false;
        }
    }
    /**
     * 删除问卷，和与问卷相关的数据
     * @param $wechat_id
     * @param $id
     * @param $token
     */
    public function questionDel($wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){
            return ajaxReturn([],"验证失败",1);
        }
        $del['wechat_id']   =   scafelEncrypt($wechat_id,"D");
        $del['question_id'] =   $id;
        DB::beginTransaction();
        $num1 = DB::table("wechat_question")->where(data2where($del))->delete();
        $num2 = DB::table("wechat_question_code_list")->where(data2where($del))->delete();
        $num3 = DB::table("wechat_question_answer")->where(data2where($del))->delete();
        $num4 = DB::table("wechat_question_tips")->where(data2where($del))->delete();
        if($num1 > -1&& $num2 > -1 && $num3 > -1 &&$num4 > -1){
            DB::commit();
            return ajaxReturn([],"删除成功",0);
        }else{
            DB::rollBack();
            return ajaxReturn([],"删除失败",1);
        }
    }
}
