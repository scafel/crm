@include('home.common.header')
<style>
    td{
        border: 1px solid black;
    }
</style>
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>搜索：</li>
                <li>
                    <input type="text" id="test16"  name="time_id" class="input layui-input" style="width:250px; line-height:17px;display:inline-block" placeholder="年-月"/>
                    <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
                <li>
                    <a href="javascript:void(0)" id="a" class="button border-main icon-expand" onclick="dumpMessage()" >导出本页数据</a></li>
                </li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">时间</th>
                @foreach($department as $value)
                    <th value="{{ $value->id }}">{{ $value->name }}</th>
                @endforeach
                <th>合计</th>
            </tr>
            @if(empty($time))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        本月还没有用户记录
                    </td>
                </tr>
            @else
                @foreach($time as $value)
                    <div style="display: none;">{{ $number1 = 0 }}</div>
                    <tr>
                        <td class="scafel_td">{{ $value->time_id }}</td>
                        @foreach($user[$value->time_id] as $v)
                            <td><a href="javascript:findUser('{{ $value->time_id }}','{{ $number1++}}','department')">{{ $v }}</a></td>
                        @endforeach
                        <td><a href="javascript:findUser('{{ $value->time_id }}',-1,'department')">{{ $total_cos[$value->time_id] }}</a> </td>
                    </tr>
                @endforeach
                <tr>
                    <td>合计</td>
                    @foreach($number as $key=>$v)
                        <td><a href="javascript:findUser(-1,'{{ $number2++ }}','department')">{{ $v }}</a></td>
                    @endforeach
                    <td><a href="javascript:findUser(-1,-1,'channel')">{{ $total_all }}</a></td>
                </tr>
                <tr>
                    <div class="pagelist">  </div>
                </tr>
            @endif
        </table>
    </div>
</form>
@include('home.common.footer')
<script>
    laydate.render({
        elem: '#test16'
        ,type: 'month'
    });
    function changesearch() {
        layer.load();
        var value = $("#test16").val();
        if (!value){layer.alert("没有内容输入");layer.closeAll("loading");return false;};
        $.ajax({
            url:"{{ url('home/page/user/departmentsearch') }}",
            type:'post',
            data:{value:value},
            success:function (res) {

                if (res.errorcode == 1){
                    layer.closeAll("loading");
                    layer.alert(res.msg);
                } else if (res.errorcode == 0) {
                    window.location.href = "{{ url('home/page/user/departmentlist') }}"+"?time_id="+res.data.time_id
                }else{
                    layer.closeAll("loading");
                    layer.alert("请求出错");
                }
            },
            error:function () {
                layer.closeAll("loading");
                layer.alert("请求出错");
            }
        })
    }
    function dumpMessage() {
        if(GetQueryString("time_id")){
            window.location.href    =   window.location.href+"&is_show=1";
        }else{
            window.location.href    =   window.location.href+"?is_show=1";
        }

    }

    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }
</script>
