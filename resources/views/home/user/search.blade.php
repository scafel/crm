@include("home.common.header")
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>姓名</th>
                <th>联系电话</th>
                <th>就诊科室</th>
                <th>来诊渠道</th>
                <th width="10%">添加时间</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($user))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        未找到信息
                    </td>
                </tr>
            @else
                @foreach($user as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;">
                            {{ $value->id }}</td>
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->tel }}</td>
                        <td>{{ getDepartmentNameById($value->department_id) }}</td>
                        <td>{{ getChannelNameById($value->channel_id) }}</td>
                        <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>

                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="javascript:void(0);" onclick="showUserInfo('{{ $value->id }}/{{ idMd5Token($value->id) }}')"><span class="icon-eye-slash"></span> 查看全部信息</a>
                                <a class="button border-red" href="javascript:void(0);" onclick="selectUser('{{ $value->id }}/{{ idMd5Token($value->id) }}')"><span class="icon-send"></span> 选择</a>
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
@include("home.common.footer")
<script>
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    function selectUser(id){
        parent.setUserMessage(id);
        parent.layer.close(index);
    };
</script>