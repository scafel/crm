<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 微信系统   问卷类控制器
 * Class QuestionController
 * @package App\Http\Controllers\Wechat
 */
class QuestionController extends Controller
{
    /**
     * 根据问卷id获取问卷内容，并展示到页面中
     * @param Request $request
     * @param $id
     */
    public function loadPage(Request $request,$wechat_id){
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['isshow']    =   1;
        $view['list']   =   DB::table("wechat_question")->where(data2where($where))->get();
        $view['wechat_id']  =   $wechat_id;
        return view("wechat.question.page",$view);
    }
    public function loadQuestionPage(Request $request,$wechat_id,$id,$token){
        if (!idMd5Token($id,$token)){return ajaxReturn([],"message MD5 error",1);}
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $where['question_id']   =   $id;
        $view['question']   =   DB::table("wechat_question")->where(data2where($where))->first();
        $view['answer']     =   $this->getRandArray(unserialize($view['question']->answer),10);
        $view['wechat_id']  =   $wechat_id;
        return view("wechat.question.answer",$view);
    }
    /**
     * 提交问卷并返回问卷结果
     * @param Request $request
     */
    public function submitQuertion(Request $request,$wechat_id){
        $num    =   0;
        $data   =   $request->input();
        if (!idMd5Token($data['question_id'],$data['question_token'])){return ajaxReturn([],"校验失败",1);}
        $question_id   =   $data['question_id'];
        unset($data['_token']);unset($data['question_token']);unset($data['question_id']);
        foreach ($data as $value){
            if ((int)$value > -1 && (int)$value <3){
                $num += (int)$value;
            }
        }
        $list   =   DB::table("wechat_question_code_list")->where("question_id","=",$question_id)->where("code",">",$num)->orderBy("code","asc")->first();
        $tips   =   DB::table("wechat_question_tips")->where("question_id","=",$question_id)->first();
        $view['article']    =   $tips;
        $view['total']  =   $num;
        $view['list']   =   $list;
        $view['wechat_id']  =   $wechat_id;
        return view("wechat.question.result",$view);
    }

    public function getRandArray($result,$num,$return = []){
        $end    =   count($result) -1;
        $numbers = range (0,$end);
        shuffle ($numbers);//随机打乱数组排序
        $numbersrand = array_slice($numbers,0,$num);//截取数组的某一段
        foreach ($result as $key=>$value){
            if (in_array($key,$numbersrand)){
                $return[]   =   $value;
            }
        }
        return $return;
    }
}
