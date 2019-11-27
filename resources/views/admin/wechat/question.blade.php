@include("home.common.header")
<body>
<form method="post" action="" id="listform">
    <div class="panel admin-panel">
        <div class="panel-head"><strong class="icon-reorder"> 问卷列表（微信端放置网址   {{url("wechat")}}/{{ $wechat }}/question）</strong> </div>
        <div class="padding border-bottom">
            <ul class="search" style="padding-left:10px;">
                <li> <a class="button border-main icon-plus-square-o" href="question/add"> 添加内容</a> </li>
            </ul>
        </div>
        <table class="table table-hover text-center " style="table-layout: fixed;">
            <tr>
                <th width="100" style="text-align:left; padding-left:20px;">ID</th>
                <th>名称</th>
                <th>链接</th>
                <th width="10%">更新时间</th>
                <th width="310">操作</th>
            </tr>
            @if(empty($list))
                <tr>
                    <td colspan="6">
                        没有问卷，请先添加一个问卷再试
                    </td>
                </tr>
            @else
                @foreach($list as $value)
                    <tr>
                        <td style="text-align:left; padding-left:20px;">
                            <input type="checkbox" name="id[]" value="" />{{ $value->id }}
                        </td>
                        <td>{{ $value->question_name }}</td>
                        <td style="overflow: hidden;text-overflow: ellipsis;">{{url("wechat")}}/{{ $wechat }}/question/page/{{ $value->question_id }}/{{ idMd5Token($value->question_id) }}</td>
                        <td>{{ date('Y-m-d',(int)$value->addtime) }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-main" href="question/show/{{$value->question_id}}/{{ idMd5Token($value->question_id) }}">
                                    <span class="icon-eye"></span> 查看
                                </a>
                                <a class="button border-yellow" href="question/edit/{{$value->question_id}}/{{ idMd5Token($value->question_id) }}">
                                    <span class="icon-edit"></span> 修改
                                </a>
                                <a class="button border-main" href="question/addcode/{{$value->question_id}}/{{ idMd5Token($value->question_id) }}">
                                    <span class="icon-adn"></span> 分值
                                </a>
                                <a class="button border-main" href="question/addtips/{{$value->question_id}}/{{ idMd5Token($value->question_id) }}">
                                    <span class="icon-adn"></span> 友情提示
                                </a>
                                <a class="button border-red" href="javascript:void(0)" onclick="del('question/del/{{$value->question_id}}/{{ idMd5Token($value->question_id) }}')">
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