<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    //

    public function __construct()
    {
        if(session("user_wechat_id_login")){

        }else{
            (new WechatController(1))->getUserInfo();
        }
    }
}
