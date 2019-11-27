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
                        <option value="2">患者姓名</option>
                        <option value="4">时间段</option>
                        <option value="5">手机号</option>
                        <option value="6">住院号</option>
                        <option value="7">床位/病房号</option>
                    </select>
                </li>
                <li>
                    <input id="test16" type="text" placeholder="" name="keywords" class="input" style="width:250px; line-height:17px;display:inline-block" readonly/>
                    <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearch()" > 搜索</a></li>
            </ul>
            <ul class="search" style="padding-left:10px;">
                <li>等级选择：</li>
                @foreach($class as $value)
                  <li>  <a href="javascript:void(0)" class="button border-main" onclick="searchMessageCustom('{{ $value->id }}')" > {{ $value->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <table class="table table-hover text-center">
            <tr>
                <th>患者姓名</th>
                <th>年龄</th>
                <th>电话</th>
                <th>住址</th>
                <th>就诊科室</th>
                <th>来院渠道</th>
                <th width="10%">患者备注</th>
                <th>导诊日期</th>
                <th>住院日期</th>
                <th width="10%">备注</th>
                <th>操作</th>
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
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->age }}</td>
                        <td>{{ $value->tel }}</td>
                        <td>{{ $value->addr }}</td>
                        <td>{{ getDepartmentNameById($value->department_id) }}</td>
                        <td>{{ getChannelNameById($value->channel_id) }}</td>
                        <td>{{ $value->remarks }}</td>
                        <td>{{ date("Y-m-d",(int)$value->addtime) }}</td>
                        <td>@if($value->intime){{ date("Y-m-d",(int)$value->intime) }}@else 未记录 @endif</td>
                        <td>{{ $value->lastremarks }}</td>
                        <td>
                            <div class="button-group">
                                <a class="button border-green" href="javascript:void(0);" onclick="openUrl('{{ url('home/page/custom/showh') }}/{{ $value->custom_id }}/{{ idMd5Token($value->custom_id) }}','加载服务历史记录')"><span class="icon-eye"></span>查看历史信息</a>
                                <a class="button border-green" href="javascript:void(0);" onclick="openUrl('{{ url('home/page/custom/addc') }}/{{ $value->custom_id }}/{{ idMd5Token($value->custom_id) }}','加载服务历史记录')"><span class="icon-edit"></span>记录内容</a>
                                @if($value->custom_status == 1)<a class="button border-red" href="javascript:void(0);" onclick="del('{{ url('home/page/custom/del') }}/{{ $value->custom_id }}/{{ idMd5Token($value->custom_id) }}')"><span class="icon-trash-o"></span> 删除</a>@else<a class="button border-red" href="javascript:void(0);">已删除</a>@endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="11"><div class="pagelist"> {{ $list->links() }} </div></td>
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
            case 6:
                changeInputName('hisnumber',false,'text');
                break;
            case 7:
                changeInputName('bednumber',false,'text');
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
            url:"{{ url('home/page/custom/search') }}",
            type:'post',
            data:{name:name,value:value},
            success:function (res) {
                layer.closeAll("loading");
                if (res.errorcode == 1){
                    layer.alert(res.msg);
                } else if (res.errorcode == 0) {
                    window.location.href = "{{ url('home/page/custom/outtime/search/') }}"+"/"+res.data.url
                }else{
                    layer.alert("请求出错");
                }
            },
            error:function () {
                layer.closeAll("loading");
                layer.alert("未开发或未完善");
            }
        })
    }
    function searchMessageCustom(id) {
        window.location.href = "{{ url('home/page/custom/searchclass') }}/1/"+id;
    }
    function outHispitle(url) {
        layer.open({
            type: 1,
            title: '选择出院时间' ,
            shadeClose: true,
            shade: true,
            area:'600px',
            btn: ['确定', '取消'],
            maxmin: false, //开启最大化最小化按钮
            content: "<div class=\"form-group\">\n" +
                "                    <div class=\"label\">\n" +
                "                        <label>出院时间：</label>\n" +
                "                    </div>\n" +
                "                    <div class=\"field\">\n" +
                "                        <input id='test123456' type=\"text\" class=\"input w50\" value=\"\" name=\"outtime\" autocomplete='off'/>\n" +
                "                        <div class=\"tips\"></div>\n" +
                "                    </div>\n" +
                "                </div><script>laydate.render({elem: '#test123456'})<\/script>"
            ,yes: function(index, layero){
                var outtime = $("#test123456").val();
                if (!outtime){
                    layer.alert('请选择日期');return false;
                }
                layer.closeAll();
                getUrl(url+"?outtime="+outtime);
            }
            ,btn2: function(index, layero){
                layer.closeAll();
            }
        });
    }
</script>
