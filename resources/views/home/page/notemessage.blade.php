@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder">用户及服务详情</strong> </div>
        <div>用户信息</div>
        <hr>
        <div>
            <div>
                <p>姓名：{{ $users->username }}</p>&nbsp;&nbsp;&nbsp;
                <p>年龄：{{ $users->age }}</p>&nbsp;&nbsp;&nbsp;
                <p>性别：{{ $users->gander == 1? '男':'女' }}</p>&nbsp;&nbsp;&nbsp;
                <p>来院渠道：{{ $users->channel }}</p>&nbsp;&nbsp;&nbsp;
                <p>就诊科室：{{ $users->department }}</p>&nbsp;&nbsp;&nbsp;
                <p>添加人员：{{ $users->admin }}</p>&nbsp;&nbsp;&nbsp;
                <p>备注：{{ $users->remarks }}</p>&nbsp;&nbsp;&nbsp;
            </div>
        </div>
        <hr>
        <div>服务信息</div>
        <hr>
        <div>
            <div>
                <p>这次哪里不舒服？{{ $custom->question_one }}</p>&nbsp;&nbsp;&nbsp;
                <p>平时还有哪里不舒服？{{ $custom->question_two }}</p>&nbsp;&nbsp;&nbsp;
                <br>
                @foreach($remarks as $value)
                    <p>添加时间：{{ date('Y-m-d H:i:s',(int)$value['addtime']) }}</p>&nbsp;&nbsp;&nbsp;
                    <p>备注：{{ $value['remarks'] }}</p>&nbsp;&nbsp;&nbsp;
                    <p>预约下次访问时间：{{ date('Y-m-d H:i:s',(int)$value['nexttime']) }}</p>&nbsp;&nbsp;<br>
                @endforeach
                <p>客服人员：{{ $custom->admin }}</p>&nbsp;&nbsp;
            </div>
        </div>
        <hr>
        <div>通知消息记录</div>
        <hr>
        <div>
            <div>
                @foreach($notepad as $value)
                    <p>添加时间{{ date('Y-m-d H:i:s',(int)($value->addtime)) }}</p>&nbsp;&nbsp;&nbsp;
                    <p>提示消息：{{ $value->message }}</p>&nbsp;&nbsp;&nbsp;
                    <p>{{ $value->isread == 1?"已阅读":"未阅读" }}</p>&nbsp;&nbsp;&nbsp;
                    <p>{{ $value->isrun == 1?"已执行":"未执行" }}</p>&nbsp;&nbsp;&nbsp;
                    <p>执行时间：{{ date('Y-m-d H:i:s',(int)($value->runtime)) }}</p>&nbsp;&nbsp;&nbsp;<br>

                @endforeach
            </div>
        </div>
    </div>
</form>
@include('home.common.footer')