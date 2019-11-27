@include('home.common.header')
<body style="background-color:#f2f9fd;">
<div class="header bg-main">
    <div class="logo margin-big-left fadein-top">
        <h1><img src="/static/home/images/y.jpg" class="radius-circle rotate-hover" height="50" alt="" />微信后台管理单位</h1>
    </div>
    <div class="head-l">
        @if(session('user_id_login') == 1)
        <a href="javascript:getUrl('{{ url('home/page/clearcache') }}');" class="button button-little bg-blue">
            <span class="icon-wrench"></span> 清除缓存
        </a> &nbsp;&nbsp;
        @endif
        <a class="button button-little bg-gray" href="{{ url('home/page') }}">
            <span class="icon-backward"></span> 返回后台主页
        </a>
        <a class="button button-little bg-red" href="javascript:getUrl('{{ url('home/login/logout') }}');">
            <span class="icon-power-off"></span> 退出登录
        </a>
    </div>
</div>
<div class="leftnav">
    <div class="leftnav-title"><strong><span class="icon-list"></span>菜单列表</strong></div>
    <h2><span class="icon-user"></span>基本设置</h2>
    <ul style="display:block">
        <li><a href="admin/keywords" target="right"><span class="icon-caret-right"></span>关键词设置</a></li>
        <li><a href="admin/question" target="right"><span class="icon-caret-right"></span>问卷设置</a></li>
        <li><a href="admin/games" target="right"><span class="icon-caret-right"></span>游戏设置</a></li>
        <li><a href="admin/menu" target="right"><span class="icon-caret-right"></span>菜单管理</a></li>
    </ul>
</div>
<ul class="bread">
    <li><a href="{{ url('home/page/webinfo') }}" target="right" class="icon-home"> 首页</a></li>
    <li><a href="##" id="a_leader_txt">网站信息</a></li>
</ul>
<div class="admin">
    <iframe scrolling="auto" rameborder="0" src="{{ url('home/page/webinfo') }}" name="right" width="100%" height="99%" style="min-height:200px;" ></iframe>
</div>
@include('home.common.footer1')
<script type="text/javascript">
    $(function(){
        $(".leftnav h2").click(function(){
            $(".leftnav ul").hide(200);
            $(this).next().slideToggle(200);
            $(this).toggleClass("on");
        })
        $(".leftnav ul li a").click(function(){
            $("#a_leader_txt").text($(this).text());
            $(".leftnav ul li a").removeClass("on");
            $(this).addClass("on");
        })
    });
</script>