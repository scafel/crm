@include('home.common.header')
<body>
<div class="bg"></div>
<div class="container">
    <div class="line bouncein">
        <div class="xs6 xm4 xs3-move xm4-move">
            <div style="height:150px;"></div>
            <div class="media media-y margin-big-bottom">
                <div class="text-center margin-big padding-big-top"><h1>欢迎来到阳泉桃河医院crm系统</h1></div>

                @if(session('user_id_login'))
                    <a href="{{ url('home/page') }}">进入系统</a>
                @else
                    <a href="{{ url('home/login') }}">登陆</a>
                @endif
            </div>
            <form method="post" action="" id="listform">
                <div class="panel admin-panel">
                    <div class="panel-head"><strong class="icon-reorder"> 最近添加客户</strong> </div>
                    <table class="table table-hover text-center">
                        <tr>
                            <th>姓名</th>
                            <th>添加时间</th>
                        </tr>
                        @if(empty($user))
                            <tr>
                                <td style="text-align:left; padding-left:20px;" colspan="5">
                                    还没有任何登记用户
                                </td>
                            </tr>
                        @else
                            @foreach($user as $value)
                                <tr>
                                    <td>{{ $value->username }}</td>
                                    <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
@include('home.common.footer')