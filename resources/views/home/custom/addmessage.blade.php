@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>预约下次回访</strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/custom/addc') }}" method="post" class="form-x">
            <input id="token" type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $id }}" id="id">
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
                    <label>本次回访备注：</label>
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
        ,range: false
    });
</script>