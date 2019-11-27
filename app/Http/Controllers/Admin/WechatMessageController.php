<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WechatMessageController extends Controller
{
    /**
     * 接收到文本消息并返回
     * @param $data
     */
    public function TextMessage($data,$wechat_id){
        $time = time();
        $content =   trim($data->Content);
        if($this->keyWordsRturn($data,$content,$wechat_id)){
            wechatLog($this->keyWordsRturn($data,$content,$wechat_id));
            echo $this->keyWordsRturn($data,$content,$wechat_id);
        };
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $msgType = "text";
        $contentStr = "我们收到了您的留言，我们会尽快联系您！";
        $resultStr = sprintf($textTpl, $data->FromUserName, $data->ToUserName, $time, $msgType, $contentStr);
        echo $resultStr;exit();
    }
    /**
     * 接收到关注事件并返回
     * @param $data
     * @return bool
     */
    public function EventMessage($data,$wechat_id){
        $user   =   DB::table('wechat_user')->where("openid",'=',$data->FromUserName)->find();
        if(!empty($user) && $user['id']){
            return true;exit();
        }
        switch ($data->Event){
            case 'subscribe':
            case 'SCAN':
                $wechat =   new WechatController();
                $user   =   $wechat->getUserInfoByOpenid($data->FromUserName);
                $user['openid'] =   $data->FromUserName;
                $pid    =   $data->EventKey;
                $num    =   explode('qrscene_',$pid);
                $user['pid']    =   $num[0]?$num[0]:$num[1];
                if ($user['pid']){
                    UserController::editScor($user['pid'],5);
                }
                if($end = createtabledata('wechat_user',$user)){
                    $id     =   DB::table('wechat_user')->insert($end);
                    session(['user_id_wechat'=>$id]);
                    $user['id'] =   $id;
                }
                break;
            case 'unsubscribe':
                break;
        }
        return true;

    }
    /**
     * 接收到图片信息并返回
     * @param $data
     */
    public function ImageMessage($data){

    }
    /**
     * 接收到语音信息并返回
     * @param $data
     */
    public function VoiceMessage($data){

    }
    /**
     * 接收到普通视频消息并返回
     * @param $data
     */
    public function VideoMessage($data){

    }
    /**
     * 接收短视频消息并返回
     * @param $data
     */
    public function ShortvideoMessage($data){

    }
    /**
     * 接收到地理位置信息并返回
     * @param $data
     */
    public function LocationMessage($data){

    }
    /**
     * 接收到链接消息并返回
     * @param $data
     */
    public function LinkMessage($data){

    }
    /**
     * 其他类型消息并返回
     * @param $data
     */
    public function OtherMessage($data,$wechat_id){
        $time = time();
        $title  =   "scafel.top Personal Development";
        $description = "scafel.top is a personal website with only one contact.";
        $picurl =   "http://crm.yqthyy.com/time.jpg";
        $url    =   "http://www.scafel.top";
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime><![CDATA[%s]]></CreateTime><MsgType><![CDATA[%s]]></MsgType><ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[{$title}]]></Title><Description><![CDATA[{$description}]]></Description><PicUrl><![CDATA[{$picurl}]]></PicUrl><Url><![CDATA[{$url}]]></Url></item></Articles></xml>";
        $msgType = "news";
        $resultStr = sprintf($textTpl, $data->FromUserName, $data->ToUserName, $time, $msgType);
        echo $resultStr;exit();
    }
    /**
     * 错误接收
     */
    public function ErrorMessage(){
        echo true;
    }
    /**
     * 关键词回复
     * @param $data
     * @param $keywords
     * @return string
     */
    private function keyWordsRturn($data,$keywords,$wechat_id){
        $where['keyname']   =   $keywords;
        $where['status']    =   1;
        $where['wechat_id'] =   scafelEncrypt($wechat_id,"D");
        $time   =   time();
        $keywordsf   =   DB::table("wechat_keywords")->where(data2where($where))->first();
        if ($keywordsf){}else{return false;}
        switch ($keywordsf->returntype){
            case 0:
                $type   =   "text";
                $contentStr =   $keywordsf->message;
                $textTpl    =   $this->xmlReturn($type);
                $resultStr = sprintf($textTpl, $data->FromUserName, $data->ToUserName, $time, $type, $contentStr);
                return $resultStr;
                break;
            case 1:
                $type   =   "news";
                $title  =   $keywordsf->returnname;
                $description = $keywordsf->message;
                $picurl =   $keywordsf->img;
                $url    =   $keywordsf->url;
                $textTpl    =   $this->xmlReturn($type);
                $resultStr = sprintf($textTpl, $data->FromUserName, $data->ToUserName, $time, $type, $title,$description,$picurl,$url);
                return $resultStr;
                break;
            case 2:
                $type   =   "image";
                $textTpl    =   $this->xmlReturn($type);
                $mediaid    =   $keywordsf->img;
                $resultStr = sprintf($textTpl, $data->FromUserName, $data->ToUserName, $time, $type, $mediaid);
                return $resultStr;
                break;
            default :
                return false;
        }
    }
    /**
     * 发送模板消息
     * @param string $openid
     * @param array $message
     * @param int $num
     * @param string $url
     * @return bool
     */
    private function sendMessageToUser(string $openid,array $message,string $template_id,string $url){
        $wechat =   new WechatController();
        $token  =   $wechat->getAToken();
        $data   =   array(
            'touser' =>  $openid,
            'template_id'=>$template_id,
            'url'   =>  $url,
            'data'  =>  $message
        );
        $data   =   json_encode($data);
        $url    =   "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        $message    =   json_decode(http_curl($url,$data,'POST'),true);
        if ($message['errcode']){
            ajaxTips($message['errmsg']);exit();
        }else{
            return true;
        }

    }
    /**
     * 获取模板列表
     */
    private function template($num){
        $wechat =   new WechatController();
        $token  =   $wechat->getAToken();
        $url    =   "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
        $message    =   json_decode(http_curl($url),true);
        return $message['template_list'][$num];
    }
    /**
     * 微信xml格式数据返回
     * @param $type
     * @return bool|string
     */
    private function xmlReturn($type){
        $str    =   "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType>";
        switch ($type){
            case "text":
                $str    .= "<Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag>";
                break;
            case "image":
                $str    .=  "<Image><MediaId><![CDATA[%s]]></MediaId></Image>";
                break;
            case "voice":
                $str    .=  "<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>";
                break;
            case "video":
                $str    .=  "<Video><MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title><Description><![CDATA[description]]></Description></Video>";
                break;
            case "music":
                $str    .=  "<Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music>";
                break;
            case "news":
                $str    .=  "<ArticleCount>1</ArticleCount><Articles><item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item></Articles>";
                break;
            default :
                return false;
        }
        return $str .=  "</xml>";
    }
    /**
     * 上传媒体素材
     */
    private function addMaterial(){
        $url    =   "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN";
    }
    /**
     * 获取全局access Token值
     * @return mixed
     */
    private function getAccessToken(){
        $wechat =   DB::table('wechat')->where("id","=",session('wechat_id'))->first();
        if(Cache::has("global_access_token".$wechat->id)){
            return Cache::get("global_access_token".$wechat->id);
        }
        $appid  =   $wechat->appid;
        $appsecret  =   $wechat->appsecret;
        $url    =   "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $curlResult =   json_decode(httpRequest($url,get),true);
        if (isset($curlResult['errcode']) && $curlResult['errcode']){
            return $curlResult;
        }else{
            Cache::put("global_access_token".$wechat->id,$curlResult['access_token'],$curlResult['expires_in']-60);
            return $curlResult['access_token'];
        }
    }
}