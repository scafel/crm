@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> <a href="" style="float:right; display:none;">添加字段</a></div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>搜索：</li>
                <li>
                    <select name="cid" class="input" style="width:200px; line-height:17px;" onchange="changeSearchInput(this)">
                        <option value="0">选择要搜索的类型</option>
                        <option value="1">年龄</option>
                        <option value="2">姓名</option>
                        <option value="3">住址</option>
                        <option value="5">联系电话</option>
                        <option value="4">时间段</option>
                    </select>
                </li>
                <li>
                    <input id="test16" type="text" placeholder="" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" readonly/>
                    <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
                <li><a href="{{ url('home/page/custom/appointment/add') }}" class="button border-green icon-edit" >添加新预约</a></li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>姓名</th>
                <th>联系电话</th>
                <th width="10%">添加时间</th>
                <th>是否到诊</th>
                <th width="310">操作</th>
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
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="" />
                            {{ $value->id }}</td>
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->tel }}</td>
                        <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>
                        <td>@if($value->user_id) <a href="javascript:void(0);" onclick="showUserInfo('{{ $value->user_id }}/{{ idMd5Token($value->user_id) }}')">记录到诊，到诊ID：{{ $value->user_id }}</a> @else 未记录到诊 @endif</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-red" href="javascript:void(0);" onclick="del('{{ url('home/page/custom/appointment/del') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}')"><span class="icon-trash-o"></span> 删除</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"><div class="pagelist"> {{ $user->links() }} </div></td>
                </tr>
            @endif
        </table>
    </div>
</form>
@include('home.common.footer')
<script>
    function changeSearchInput(event){
        var type   =   parseInt($(event).val());
        switch (type) {
            case 1:
                changeInputName('age',false,'number');
                break;
            case 2:
                changeInputName('username',false,'text');
                break;
            case 3:
                changeInputName('addr',false,'text');
                break;
            case 4:
                changeInputName('addtime',false,'text');
                laydate.render({
                    elem: '#test16'
                    ,range: true
                });
                break;
            case 5:
                changeInputName('tel',false,'text');
                break;
            default:
                changeInputName('orther',true,'text');
        }
    }
    function changeInputName(name,read,type) {
        $("#test16").val("");
        $("#test16").attr('name',name);
        $("#test16").attr('type',type);
        $("#test16").attr('readonly',read);
    }
    function changesearch() {
        layer.load();
        var name = $("#test16").attr("name");
        var value = $("#test16").val();
        $.ajax({
            url:"{{ url('home/page/user/search') }}",
            type:'post',
            data:{name:name,value:value},
            success:function (res) {
                layer.closeAll("loading");
                if (res.errorcode == 1){
                    layer.alert(res.msg);
                } else if (res.errorcode == 0) {
                    window.location.href = "{{ url('home/page/custom/appointment/search') }}"+"?type="+res.data.url
                }else{
                    layer.alert("请求出错");
                }
            },
            error:function () {
                layer.alert("请求出错");
            }
        })
    }
</script>
