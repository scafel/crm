@include('home.common.header')
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong></div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li>搜索：</li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>姓名</th>
                <th>添加时间</th>
            </tr>
            @if(empty($list))
                <tr>
                    <td style="text-align:left; padding-left:20px;" colspan="5">
                        还没有任何登记用户
                    </td>
                </tr>
            @else
                @foreach($list as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;"><input type="checkbox" name="id[]" value="{{ $value->id }}" onchange="selectUser('{{ $value->id }}','{{ $value->username }}',this)"/>
                            {{ $value->id }}</td>
                        <td>
                            <a class="button border-main" href="javascript:void(0);" onclick="showUserInfo('{{ $value->user_id }}/{{ idMd5Token($value->user_id) }}')">
                                <span class="icon-eye-slash"></span>
                            </a>
                            {{ $value->username }}
                        </td>
                        <td>{{ date("Y-m-d H:i:s",(int)$value->addtime) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"><div class="pagelist"> {{ $list->links() }} </div></td>
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
            parent.setCustomMessage(id,name,1);
        }else{
            parent.setCustomMessage(id,name,0);
        }
    };
</script>
