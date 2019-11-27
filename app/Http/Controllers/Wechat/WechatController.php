<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WechatController extends BaseController
{
    private $wechat;
    private $redirect;

    public function __construct(int $id)
    {
        if($this->wechat   =   getWechatMessage($id)){
            $this->redirect = urlencode(url("wechat/service/redirect"));
            return $this;
        }else{
            return false;exit("未获取到该公众号的信息");
        }
    }
    public function getUserInfo(bool $type = true){
        if ($type){
            $this->getWechatCodeTypeUserInfo();
        }else{
            $this->getWechatCodeTypeBase();
        }
    }
    public function  getAccessTokenP(){
        return $this->getAccessToken();
    }
    /**
     * 获取全局access Token值
     * @return mixed
     */
    private function getAccessToken(){
        if(Cache::has("global_access_token".$this->wechat->id) && Cache::get("global_access_token".$this->wechat->id)){
            return Cache::get("global_access_token".$this->wechat->id);
        }
        $appid  =   $this->wechat->appid;
        $appsecret  =   $this->wechat->appsecret;
        $url    =   "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $curlResult =   json_decode(httpRequest($url),true);
        if (isset($curlResult['errcode']) && $curlResult['errcode']){
            return $curlResult;
        }else{
            Cache::put("global_access_token".$this->wechat->id,$curlResult['access_token'],$curlResult['expires_in']-60);
            return $curlResult['access_token'];
        }
    }
    public function wechatRedirectUri(Request $request){
        wechatLog($request->input());
    }
    private function getWechatCodeTypeBase(){
        $url    =   "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->wechat->appid}&redirect_uri={$this->redirect}&response_type=code&scope=snsapi_base&state=base#wechat_redirect";
        header("Refresh:0;url={$url}");
    }
    private function getWechatCodeTypeUserInfo(){
        $url    =   "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->wechat->appid}&redirect_uri={$this->redirect}&response_type=code&scope=snsapi_userinfo&state=info#wechat_redirect";
        header("Refresh:0;url={$url}");
    }
    private function getWechatUserInfoByOpenid(){
        $url    =   "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$this->getAccessToken()}&openid={$this->user->openid}&lang=zh_CN";
    }
}
