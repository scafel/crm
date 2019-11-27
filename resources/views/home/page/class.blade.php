@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> </div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>
                    <a href="javascript:void(0)" class="button border-main icon-edit" onclick="addNewChannel()" > 添加新等级</a></li>
                <li>
                    <a href="javascript:void(0)" class="button border-main icon-medkit" onclick="mergeChannel()" > 合并多个等级</a></li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>名称</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($class))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何登记信息
                    </td>
                </tr>
            @else
                @foreach($class as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="" />
                            {{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="javascript:editChannel('{{ $value->id }}');"><span class="icon-edit"></span> 修改</a>
                                @if($value->status)
                                    <a class="button border-main" href="javascript:getUrl('{{ url('home/page/class/del') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}?type=1');"><span class="icon-edit"></span> 不显示</a>
                                @else
                                    <a class="button border-main" href="javascript:getUrl('{{ url('home/page/class/del') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}?type=0');"><span class="icon-edit"></span> 显示</a>
                                @endif
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
    function addNewChannel() {
        layer.open({
            type: 2,
            title: '查询人员信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/class/add') }}"
        })
    }
    function mergeChannel() {
        layer.open({
            type: 2,
            title: '查询人员信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/class/merge') }}"
        })
    }
    function editChannel(id) {
        layer.open({
            type: 2,
            title: '查询人员信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/class/edit') }}/"+id
        })
    }
</script>
