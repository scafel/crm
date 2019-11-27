@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> </div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>
                    <a href="javascript:void(0)" class="button border-main icon-edit" onclick="addNewDepartment()" > 添加新科室</a></li>
                <li>
                    <a href="javascript:void(0)" class="button border-main icon-medkit" onclick="mergeDepartment()" > 合并多个渠道</a></li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>名称</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($list))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何登记信息
                    </td>
                </tr>
            @else
                @foreach($list as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="" />
                            {{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="javascript:editDepartment('{{ $value->id }}');"><span class="icon-edit"></span> 修改</a>
                                @if($value->status)
                                    <a class="button border-main" href="javascript:getUrl('{{ url('home/page/department/del') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}?type=1');"><span class="icon-edit"></span> 不显示</a>
                                @else
                                    <a class="button border-main" href="javascript:getUrl('{{ url('home/page/department/del') }}/{{ $value->id }}/{{ idMd5Token($value->id) }}?type=0');"><span class="icon-edit"></span> 显示</a>
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
    function addNewDepartment() {
        layer.open({
            type: 2,
            title: '添加信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/department/add') }}"
        })
    }
    function mergeDepartment() {
        layer.open({
            type: 2,
            title: '选择合并',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/department/merge') }}"
        })
    }
    function editDepartment(id) {
        layer.open({
            type: 2,
            title: '修改信息',
            shade:0.5,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/department/edit') }}/"+id
        })
    }
</script>