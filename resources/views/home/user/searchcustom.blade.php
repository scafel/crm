@include("home.common.header")
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <table class="table table-hover text-center">
            <tr>
                <th>姓名</th>
                <th>性别</th>
                <th>联系电话</th>
                <th>就诊科室</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($users))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        未找到信息
                    </td>
                </tr>
            @else
                @foreach($users as $value)
                    <tr>
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->tel }}</td>
                        <td>{{ getDepartmentNameById($value->department_id) }}</td>
                        <td>
                            <div class="button-group">
                               <a class="button border-red" href="javascript:void(0);" onclick="selectUser('{{ $value->id }}/{{ idMd5Token($value->id) }}')"><span class="icon-send"></span> 选择</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"><div class="pagelist"> {{ $users->links() }} </div></td>
                </tr>
            @endif
        </table>
    </div>
</form>
@include("home.common.footer")
<script>
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    function selectUser(id){
        parent.setUserMessage(id);
        parent.layer.close(index);
    };
</script>