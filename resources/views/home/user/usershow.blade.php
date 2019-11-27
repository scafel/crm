@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong> </div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>只有没有分级用户会出现在这里</li>
            </ul>
        </div>
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
                        还没有任何登记用户
                    </td>
                </tr>
            @else
                @foreach($user as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="{{ $value->id }}" onchange="selectUser('{{ $value->id }}','{{ $value->username }}',this)"/>
                            {{ $value->id }}</td>
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->tel }}</td>
                        <td>{{ getDepartmentNameById($value->department_id) }}</td>
                        <td>{{ getChannelNameById($value->channel_id) }}</td>
                        <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="javascript:void(0);" onclick="showUserInfo('{{ $value->id }}/{{ idMd5Token($value->id) }}')"><span class="icon-eye-slash"></span> 查看全部信息</a>
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
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    function selectUser(id,name,event){
        if($(event).is(":checked")){
            parent.setUserMessage(id,name,1);
        }else{
            parent.setUserMessage(id,name,0);
        }
    };
</script>
