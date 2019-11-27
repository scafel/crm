@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>姓名</th>
                <th>账号</th>
                <th width="10%">添加时间</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($admin))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何登记用户
                    </td>
                </tr>
            @else
                @foreach($admin as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="" />
                            {{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->username }}</td>
                        <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-yellow" href="javascript:void(0);"><span class="icon-edit"></span> 修改</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</form>
@include('home.common.footer')
<script>
    function editChannel(id) {
        layer.open({
            type: 2,
            title: '查询人员信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/user/edit') }}/"+id
        })
    }
</script>
