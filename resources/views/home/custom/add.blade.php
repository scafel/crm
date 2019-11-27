@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/custom/add') }}" method="post" class="form-x">
            <input id="token" type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="user_id" value="" id="user_id">
            <div class="form-group selectUser">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main icon-check-square-o" type="button" onclick="selectUser()"> 选择已有患者</button>
                    <button class="button bg-main icon-check-square-o" type="button"  onclick="addUser()"> 添加新患者</button>
                </div>
            </div>
            <div class="userMessage" style="display: none;">
                <div class="form-group">
                    <div class="label">
                        <label>姓名：</label>
                    </div>
                    <div class="field">
                        <input id="username" type="text" class="input w50" value="" name="username" data-validate="required:请输入患者姓名,chinese:姓名应该为中文" />
                        <div class="tips">
                            查询是否有此人<button class="button bg-main icon-check-square-o" type="button" onclick="findUser($('#username').val())"> 查询</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>性别：</label>
                    </div>
                    <div class="field">
                        <select name="gander" class="input w50" id="gander">
                            <option value="0">女</option>
                            <option value="1">男</option>
                        </select>
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>年龄：</label>
                    </div>
                    <div class="field">
                        <input  id="age" type="number" class="input w50" value="0" name="age" data-validate="required:年龄不能为空,number:年龄必须为数字" />
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>电话：</label>
                    </div>
                    <div class="field">
                        <input id="tel" type="tel" class="input w50" value="0" name="tel" data-validate="required:请输入联系电话,tel:必须为电话号码" />
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>住址：</label>
                    </div>
                    <div class="field">
                        <input id="addr" type="text" class="input w50" value="" name="addr"   />
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>就诊科室：</label>
                    </div>
                    <div class="field">
                        <select id="department_id" name="department_id" class="input w50">
                            @foreach($department as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label>来诊渠道：</label>
                    </div>
                    <div class="field">
                        <select id="channel_id" name="channel_id" class="input w50">
                            @foreach($channel as $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                        <div class="tips"></div>
                    </div>
                </div>
                <div class="form-group addtime" style="display: none;">
                    <div class="label">
                        <label>住院时间：</label>
                    </div>
                    <div class="field">
                        <input id="test165" type="text" class="input w50" value="" name="intime"/>
                        <div class="tips"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>选择等级：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    @foreach($class as $value)
                    {{ $value->name }} &nbsp;<input class="token" type="radio" name="class_id" value="{{ $value->id }}" />
                    @endforeach
                    不分级 &nbsp;<input type="radio" name="class_id" value="0" checked/>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>床位/病房号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="bednumber"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>住院号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="hisnumber"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>基本问题：</label>
                </div>
                <div class="field">
                    <div>
                        <p>这次哪里不舒服？</p>
                        <br />
                        <div class="field">
                            <textarea class="input" name="question_one" style=" height:90px;">无</textarea>
                            <div class="tips"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>基本问题：</label>
                </div>
                <div class="field">
                    <div>
                        <p>平时还有哪里不舒服?</p>
                        <br>
                        <div class="field">
                            <textarea class="input" name="question_two" style=" height:90px;">无</textarea>
                            <div class="tips"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>预计下次回访时间：</label>
                </div>
                <div class="field">
                    <input id="test166" type="text" class="input w50" value="" name="nexttime" autocomplete="off"/>
                    <div class="tips">预计下次回访时间,系统会在消息中显示，请及时处理</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>备注：</label>
                </div>
                <div class="field">
                    <textarea class="input" name="lastremarks" style=" height:90px;">无</textarea>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('home.common.footer')
<script>
    laydate.render({
        elem: '#test166'
    });
    laydate.render({
        elem: '#test165'
    });
    function addUser() {
        $("input[class!=token]").val("");
        $("input").removeAttr("readonly");
        $("input").removeAttr("disabled");
        $("select").removeAttr("disabled");
        $(".addtime").show();
        $(".userMessage").show();
    }
    function selectUser() {
        $(".userMessage").hide();
        layer.prompt({title: '输入患者姓名', formType: 3}, function(pass, index){
            layer.load("查询中");
            $.ajax({
                url:"{{ url('home/page/user/search') }}",
                type:'post',
                data:{name:'username',value:pass},
                success:function (res) {
                    layer.closeAll();
                    if (res.errorcode == 1){
                        layer.alert(res.msg);
                    } else if (res.errorcode == 0) {
                        layer.open({
                            type: 2,
                            shade: false,
                            area: ['500px','500px'],
                            maxmin: true,
                            content: "{{ url('home/page/user/ajaxsearch/username') }}",
                        })
                    }else{
                        layer.alert("请求出错");
                    }
                },
                error:function () {
                    layer.alert("请求出错");
                }
            })
        });

    }
    function findUser(username) {
        $.ajax({
            url:"{{ url('home/page/user/search') }}",
            type:'post',
            data:{name:'username',value:username},
            success:function (res) {
                layer.closeAll();
                if (res.errorcode == 1){
                    layer.alert(res.msg);
                } else if (res.errorcode == 0) {
                    layer.open({
                        type: 2,
                        shade: false,
                        area: ['500px','500px'],
                        maxmin: true,
                        offset:'rt',
                        content: "{{ url('home/page/user/ajaxsearch/username') }}",
                    })
                }else{
                    layer.alert("请求出错");
                }
            },
            error:function () {
                layer.alert("请求出错");
            }
        })
    }
    function setUserMessage(id) {
        $.ajax({
            url:"/home/page/user/userinfodata/"+id,
            type: "get",
            success:function (res) {
                layer.closeAll('loading');
                if (res.errorcode == 0){
                    var user = res.data;
                    showUser(user);
                } else{
                    layer.msg("加载出错");
                }
            },
            error:function () {
                layer.msg("请求出错！");
            }
        })
    }
    function showUser(user) {
        $(".userMessage").show();
        $("#user_id").val(user.id);
        $("#username").val(user.username);
        $("#username").attr("readonly","readonly");
        $("#age").val(user.age);
        $("#age").attr("disabled","disabled");
        $("#gander").val(user.gander);
        $("#gander").attr("disabled","disabled");
        $("#tel").val(user.tel);
        $("#tel").attr("disabled","disabled");
        $("#addr").val(user.addr);
        $("#addr").attr("disabled","disabled");
        $("#channel_id").val(user.channel_id);
        $("#channel_id").attr("disabled","disabled");
        $("#department_id").val(user.department_id);
        $("#department_id").attr("disabled","disabled");
        $(".addtime").show();
    }
</script>