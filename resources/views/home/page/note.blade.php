@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        @if(empty($note))
            <table class="table table-hover text-center">
                <tr>
                    <td>没有任何消息</td>
                </tr>
            </table>
        @else
            @foreach($note as $value)
                @if($value->isrun != 1 || $value->isread != 1)
                <table class="table table-hover text-center">
                    <tr><a href="javascript:void(0);" onclick="openUrlRead('{{ $value->id }}','阅读')">{{ $value->message }}</a></tr><br/>
                    <tr>上次回访时间：{{ date("Y-m-d",(int)$value->addtime) }}</tr><br/>
                    <tr>@if($value->isrun)<i style="color: blue;">已预约</i>@else<span style="cursor: pointer" onclick="openUrl('{{ url('home/page/custom/addc') }}/{{ $value->custom_id }}/{{ idMd5Token($value->custom_id) }}','添加服务信息');"><i style="color: red;">预约下次回访</i></span> @endif</tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <tr>@if($value->isrun)<i style="color: blue;"></i>@else<span style="cursor: pointer" onclick="getUrl('{{ url('home/page/notepad/toread') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}')"><i style="color: red;">不再预约</i></span> @endif</tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </table>
                <hr >
                @endif
            @endforeach
        @endif
    </div>
</form>
@include('home.common.footer')
<script>
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    function openUrlRead(id,name){
        parent.openUrlR(id,name);
        parent.layer.close(index);
    };
</script>
