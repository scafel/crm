<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 微信系统     商城类控制器
 * 主要加载各个页面展示，不参与数据交互
 * Class MallController
 * @package App\Http\Controllers\Wechat
 */
class MallController extends Controller
{
    private $user;
    private $goods;

    public function __construct()
    {
        $this->user = new UserController();
        $this->goods = new GoodsController();
    }

    /**
     * 商城首页
     */
    public function indexPage(){
        $view['banner'] =   (new IndexController())->banner();
        $view['goods']  =   $this->goods->getGoodsList();
        $view['adv']    =   (new IndexController())->adv();
        $view['tips']    =   (new IndexController())->tips();
        return view("wechat.mall.index",$view);
    }

    /**
     * 分类页面
     */
    public function catePage(){
        $view['cate']   =   $this->goods->getGoodsCate();
        $view['catelist']   =   $this->goods->getGoodsListByCateIdFirst();
        return view("wechat.mall.cate",$view);
    }

    /**
     * 购物车页面
     */
    public function cartPage(){
        $view['cart']   =   $this->user->getUserShoppingCart();
        return view("wechat.mall.cart",$view);
    }

    /**
     * 用户页面
     */
    public function userPage(){
        $view['userinfo']   =   $this->user->getUserInfo();
        $view['order']   =   $this->user->getUserOrder();
        return view("wechat.mall.user",$view);
    }
}
