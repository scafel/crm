@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>公众号名称：</label>
                </div>
                <div class="field">
                    <input id="wechatname" type="text" class="input w50" value="" name="wechatname" data-validate="required:请输入公众号名称" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>AppID：</label>
                </div>
                <div class="field">
                    <input id="appid" type="text" class="input w50" value="" name="appid"   />
                    <div class="tips">开发者ID</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>AppSecret：</label>
                </div>
                <div class="field">
                    <input id="appsecret" type="text" class="input w50" value="" name="appsecret"   />
                    <div class="tips">开发者密匙</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>Token：</label>
                </div>
                <div class="field">
                    <input id="token" type="text" class="input w50" value="" name="token"  readonly />
                    <div class="tips">服务器令牌</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>EncodingAESKey：</label>
                </div>
                <div class="field">
                    <input id="encodingaeskey" type="text" class="input w50" value="" name="encodingaeskey"  readonly />
                    <div class="tips">消息加解密密钥</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>URL：</label>
                </div>
                <div class="field">
                    <input id="url" type="text" class="input w50" value="" name="url"   readonly/>
                    <div class="tips">服务器地址</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>备注：</label>
                </div>
                <div class="field">
                    <textarea id="remarks" class="input" name="remarks" style=" height:90px;">无</textarea>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main icon-check-square-o" type="button" onclick="return getWechatConf()"> 生成相关配置</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('home.common.footer')
<script>
    function getWechatConf() {
        if ($('#appid').val() && $("#appsecret").val() && $("#wechatname").val()) {
            $.ajax({
                url: "{{ url('home/page/wechat/getconf') }}/{{ idMd5Token(0) }}",
                type: 'post',
                data: {appid: $('#appid').val(), appsecret: $("#appsecret").val(), wechatname: $("#wechatname").val(),remarks:$("#remarks").text()},
                success: function (res) {
                    if (res.errorcode == 1) {
                        layer.msg(res.msg);
                    } else if (res.errorcode == 0) {
                        $("#url").val(res.data.url);
                        $("#encodingaeskey").val(res.data.encodingaeskey);
                        $("#token").val(res.data.token);
                    } else {
                        layer.msg("发生错误了");
                    }
                },
                error: function (res) {
                    layer.alert("请求发生错误！");
                }
            })
        }else {
            layer.msg("请先填写内容");
            return false;
        }
    }
</script>