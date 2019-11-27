<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WechatController extends Controller
{
    /**
     * 加载微信列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wechatList(){
        $view['list']   =   DB::table('wechat')->where("status","=",1)->get();
        $view['count']  =   count($view['list']);
        return view('admin.wechat.main',$view);
    }
    /**
     * 加载添加微信页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wechatAdd(){
        return view("admin.wechat.wechatadd");
    }
    /**
     * 加载修改微信页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wechatEdit($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $view['wechat'] = DB::table('wechat')->where('id','=',$id)->first();
        return view("admin.wechat.wechatedit",$view);
    }
    /**
     * 删除登记好的微信公众号
     * @param $id
     */
    public function wechatDel($id,$token){
        if(!idMd5Token($id,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $num = DB::table('wechat')->where('id','=',$id)->update(['status'=>0]);
        if($num > -1){
            return ajaxReturn([],'请求成功',0);
        }else{
            return ajaxReturn([],'请求失败',0);
        }
    }
    /**
     * 接受微信发过来的消息
     * @param Request $request
     * @param $id
     */
    public function token(Request $request, $wechat_id ){
        $id = scafelEncrypt($wechat_id,'D');
        $wechat     =   DB::table('wechat')->where("id",'=',$id)->first();
        if (empty($wechat)){echo false;exit(false);}
        if ($wechat->isbang){
            $this->postMessage($wechat_id);
        }else{
            $data   =   $request->input();
            $echoStr = $data['echostr'];
            if ($this->checkSignature($data,$wechat->token)){
                wechatLog("微信绑定id ".$id);
                $add['isbang']  =   1;
                DB::table('wechat')->where("id",'=',$id)->update($add);
                echo $echoStr;exit();
            }else{echo  false;exit(false);}
        }
    }
    /**
     * 检查微信发过来的token是否正确
     * @return bool
     */
    private function checkSignature($data,$token)
    {
        $signature = $data["signature"];
        $timestamp = $data["timestamp"];
        $nonce = $data['nonce'];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 处理微信信息，并发送消息到微信
     */
    private function postMessage($wechat_id){
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:'';
        if (empty($postStr)){
            $postStr    =   file_get_contents("php://input");
        }
        if (!empty($postStr)){
            $add['message'] =   serialize($postStr);
            $add['addtime'] =   time();
            DB::table('wechat_message')->insert($add);
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $message    =   new WechatMessageController();
            switch ($postObj->MsgType){
                case 'text':
                    return $message->TextMessage($postObj,$wechat_id);
                    break;
                case 'event':
                    return $message->EventMessage($postObj,$wechat_id);
                    break;
                default:
                    return $message->OtherMessage($postObj,$wechat_id);
                    break;
            }
        }else{
            $message    =   new WechatMessageController();
            return $message->ErrorMessage();
        }
    }
    /**
     * 加载自定义token
     * @param Request $request
     */
    public function getWechatToken(Request $request,$token){
        if(!idMd5Token(0,$token)){
            return ajaxReturn([],'验证失败',1);
        }
        $data   =   $request->input();
        if (isset($data['appid']) && $data['appid'] && isset($data['wechatname']) && $data['wechatname'] && isset($data['appsecret']) && $data['appsecret']){}else{return ajaxReturn([],'请填写内容',1);}
        $wechat =   DB::table('wechat')->where('appid','=',$data['appid'])->first();
        if (empty($wechat)){
            $data['token']  =   get_rand_str(12,0,1);
            $data['encodingaeskey'] =   get_rand_str(43,0,1);
            $data['addtime']    =   time();
            $data['status'] =   1;
            $data['admin_id']    =   session("user_id_login");
            $end    =   createtabledata('wechat',$data);
            if($id = DB::table('wechat')->insertGetId($end)){
                $data['url']    =   url("wechat").scafelEncrypt($id)."/service/token";
                $url['url'] =   $data['url'];
                DB::table('wechat')->where("id","=",$id)->update($url);
                return ajaxReturn($data,'请求成功',0);
            }else{
                return ajaxReturn([],'请求失败',1);
            }
        }
        return ajaxReturn(object_to_array($wechat),'请求成功',0);
    }
}