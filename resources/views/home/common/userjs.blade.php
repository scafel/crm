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
        $("#test165").attr("disabled","disabled");
    }
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
                    window.location.href = "{{ url('home/page/custom/search/') }}/0"+"/"+res.data.url
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
    function searchMessageCustom(id,type) {
        window.location.href = "{{ url('home/page/custom/searchclass') }}/0/"+id;
    }
</script>