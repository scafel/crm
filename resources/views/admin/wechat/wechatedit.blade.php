@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>详情</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>公众号名称：</label>
                </div>
                <div class="field">
                    <input readonly id="wechatname" type="text" class="input w50" value="{{ $wechat->wechatname }}" name="wechatname" data-validate="required:请输入公众号名称" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>AppID：</label>
                </div>
                <div class="field">
                    <input readonly id="appid" type="text" class="input w50" value="{{ $wechat->appid }}" name="appid"   />
                    <div class="tips">开发者ID</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>AppSecret：</label>
                </div>
                <div class="field">
                    <input readonly id="appsecret" type="text" class="input w50" value="{{ $wechat->appsecret }}" name="appsecret"   />
                    <div class="tips">开发者密匙</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>Token：</label>
                </div>
                <div class="field">
                    <input id="token" type="text" class="input w50" value="{{ $wechat->token }}" name="token"  readonly />
                    <div class="tips">服务器令牌</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>EncodingAESKey：</label>
                </div>
                <div class="field">
                    <input id="encodingaeskey" type="text" class="input w50" value="{{ $wechat->encodingaeskey }}" name="encodingaeskey"  readonly />
                    <div class="tips">消息加解密密钥</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>URL：</label>
                </div>
                <div class="field">
                    <input id="url" type="text" class="input w50" value="{{ $wechat->url }}" name="url"   readonly/>
                    <div class="tips">服务器地址</div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>备注：</label>
                </div>
                <div class="field">
                    <textarea id="remarks" class="input" name="remarks" style=" height:90px;">{{ $wechat->remarks }}</textarea>
                    <div class="tips"></div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('home.common.footer')