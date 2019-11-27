/**
 * form表单ajax远程提交
 * @param id  form表单id
 * @param suburl  提交的地址
 * @param typel 成功后跳转类型   1 跳转 2刷新 其他
 * @param url 跳转url
 */
function formSubmit (id,suburl,typel,url) {
    var params = $('#'+id).serializeArray();
    console.log(params);
    var values = {},names = [];
    for( x in params ){
        if($.inArray(params[x].name,names) == -1){
            values[params[x].name] = params[x].value;
            names.push(params[x].name);
        }else{
            if($.isArray(values[params[x].name])){
                values[params[x].name].push(params[x].value);
            }else{
                var v = values[params[x].name];
                values[params[x].name] = [v,params[x].value];
            }
        }

    }
    $.ajax({
        url:suburl,
        type:"post",
        data:values,
        success:function(res){
            if(res.errorcode == 1){
                layer.alert(res.msg,{icon:4});
            }else if (res.errorcode == 0) {
                layer.msg(res.msg,{time:1000}, function(){
                    switch (typel) {
                        case 1:
                            window.location.href = url;
                            break;
                        case 2:
                            window.location.reload();
                            break;
                        default :
                            return false;
                    }
                });
            }else {
                layer.alert(res,{icon:6});
            }
        },
        error:function(){
            layer.alert('请求出错，请联系管理员');
        }
    });
}
/**
 * 文件上传
 * @param id  input[type=file] 的id
 * @param url 上传地址的url
 * @param show 显示上传地址的input[type=text] 的id
 */
function fileUpload (id,url,show) {
    var num = 0;
    var data = new FormData();
    //为FormData对象添加数据
    $.each($('#'+id)[0].files, function(i, file) {
        data.append('upload_file'+i, file);
        num = i +1;
    });
    console.log(data);
    //发送数据
    $.ajax({
        url:url,
        type:'POST',
        data:data,
        cache: false,
        contentType: false,        //不可缺参数
        processData: false,        //不可缺参数
        success:function(res){
            if (res.errorcode == 1) {
                layer.alert(res.msg,{icon:4});
            }else if (res.errorcode == 0) {
                console.log(res.data.length);
                layer.msg(res.msg, {time:2000});
                for (var i = 0 ; i < num ; i++ ){
                    $('#'+show).val(res.data['upload_file'+i].url);
                    $('#'+show).attr('data-image',res.data['upload_file'+i].url);
                    layer.msg(res.data['upload_file'+i].wechat);
                }
            }else{
                layer.alert(res,{icon:6});
            }
        },
        error:function(){
            layer.alert('上传出错',{icon:5});
        }
    });
}
/**
 * 删除一条数据
 * @param id  表的主键
 * @param token idMd5Token
 * @param table 表名
 * @param url 请求地址
 */
function delOne (id,token,table,url) {
    $.ajax({
        url:url,
        type: 'get',
        data: {id:id,token:token,table:table},
        success:function (res) {
            if(res.errorcode == 1){
                layer.alert(res.msg,{icon:4});
            }else if (res.errorcode == 0) {
                layer.msg(res.msg,{time:1500},function () {
                    window.location.reload();
                })
            }else{
                layer.alert(res,{icon:6});
            }
        },
        error:function () {
            layer.alert('操作失败',{icon:5});
        }
    })
}
/**
 * get方式直接请求某个地址
 * @param url
 */
function getUrl (url) {
    $.ajax({
        url:url,
        type:'get',
        success:function (res) {
            layer.closeAll("loading");
            if(res.errorcode == 1){
                layer.alert(res.msg,{icon:4});
            }else if (res.errorcode == 0) {
                layer.msg(res.msg,{time:1500},function () {
                    window.location.reload();
                })
            }else{
                layer.alert(res,{icon:6});
            }
        },
        error:function () {
            layer.alert('请求出错了',{icon:5})
        }
    });
}
function openUrl(url,name) {
    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
    parent.layer.open({
        type: 2,
        title: name ,
        maxmin: true, //开启最大化最小化按钮
        area: ['893px', '600px'],
        content: url
    });
}
function bigImg() {
    
}
function showUserInfo(id) {
    layer.load();
    $.ajax({
        url:"/home/page/user/userinfo/"+id,
        type: "get",
        success:function (res) {
            layer.closeAll('loading');
            if (res.errorcode == 0){
                var user = res.data;
                layer.open({
                    type: 1,
                    title: user.username+"-个人信息",
                    closeBtn: 1,
                    scrollbar: false,
                    shadeClose: true,
                    skin: 'yourclass',
                    content: strUser(user)
                })
            } else{
                layer.msg("加载出错");
            }
        },
        error:function () {
            layer.msg("请求出错！");
        }
    })
}
function strUser(user) {
    return " <div style='padding-left: 10px; padding-right: 10px;margin-top: 10px;margin-bottom: 10px;'>" +
        "   <table class=\"table table-hover text-center\">" +
        "        <tr>" +
        "          <td>姓名:</td>\n" +
        "          <td>"+user.username+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>年龄:</td>\n" +
        "          <td>"+user.age+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>性别:</td>\n" +
        "          <td>"+user.gander+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>电话:</td>\n" +
        "          <td>"+user.tel+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>就诊科室:</td>\n" +
        "          <td>"+user.department_id+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>来院渠道:</td>\n" +
        "          <td>"+user.channel_id+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>家庭住址:</td>\n" +
        "          <td>"+user.addr+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>添加时间:</td>\n" +
        "          <td>"+user.addtime+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>添加人员:</td>\n" +
        "          <td>"+user.adminname+"</td>\n" +
        "        </tr>" +
        "        <tr>" +
        "          <td>备注:</td>\n" +
        "          <td>"+user.remarks+"</td>\n" +
        "        </tr>" +
        "    </table></div>";
}
function del(url) {
    if (confirm("确定删除？")){
        layer.load();
        getUrl(url);
    }
    return false;
}