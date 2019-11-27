@include("home.common.header")
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 关键词列表</strong> </div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li> <a class="button border-main icon-plus-square-o" href="{{ url('home/page/wechat/conf') }}"> 添加内容</a> </li>
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>关键词</th>
                <th>返回信息</th>
                <th width="10%">更新时间</th>
                <th width="310">操作</th>
            </tr>
            @if($count === 0)
                <tr>
                    <td colspan="6">
                        没有关键词，请先添加一个关键词试试
                    </td>
                </tr>
            @else
                @foreach($keywords as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;">
                            <input type="checkbox" name="id[]" value="" />{{ $value->id }}
                        </td>
                        <td>{{ $value->keyname }}</td>
                        <td>{{ $value->returntype }}</td>
                        <td>{{ date('Y-m-d',(int)$value->addtime) }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="{{ url('home/page/wechat/conf/') }}/{{$value->id}}">
                                    <span class="icon-edit"></span> 查看详情
                                </a>
                                <a class="button border-red" href="javascript:void(0)" onclick="getUrl('{{ url('home/page/wechat/del') }}/{{$value->id}}')">
                                    <span class="icon-trash-o"></span> 删除
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</form>
@include("home.common.footer")