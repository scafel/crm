@include('home.common.header')
<body>
<div class="panel admin-panel margin-top">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>导出选择</strong></div>
    <div class="body-content">
        {{--<form method="post" id="form" class="form-x" action="javascript:formSubmit('form','{{ url('home/page/user/dumpuser') }}',3);">--}}
        <form method="post" id="form" class="form-x" action="{{ url('home/page/user/dumpuser') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>基本项：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    <span>年龄 <input id="ishome"  type="checkbox" name="age" checked/></span>&nbsp;&nbsp;
                    <span>性别 <input id="isvouch"  type="checkbox" name="gander" checked></span>&nbsp;&nbsp;
                    <span>姓名 <input id="istop"  type="checkbox" name="username" checked/></span>&nbsp;&nbsp;
                    <span>地址 <input id="istop"  type="checkbox" name="addr" checked/></span>&nbsp;&nbsp;
                    <span>电话 <input id="istop"  type="checkbox" name="tel" checked/></span>&nbsp;&nbsp;
                    <span>备注 <input id="istop"  type="checkbox" name="remarks" checked/></span>&nbsp;&nbsp;
                    <span>时间 <input id="istop"  type="checkbox" name="addtime" checked/></span>&nbsp;&nbsp;
                    <span>就诊科室 <input id="istop"  type="checkbox" name="department_id" checked/></span>&nbsp;&nbsp;
                    <span>来诊渠道 <input id="istop"  type="checkbox" name="channel_id" checked/></span>&nbsp;&nbsp;
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>时间选择：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    <input id="test16" type="text" placeholder="" name="timecode" class="input" style="width:250px; line-height:17px;display:inline-block"/>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>就诊科室：</label>
                </div>
                <div class="field">
                    <select name="department" class="input w50">
                        <option value="0">导出全部科室</option>
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
                    <select name="channel" class="input w50">
                        <option value="0">导出全部渠道</option>
                        @foreach($channel as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main icon-check-square-o" type="submit">导出前查看</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('home.common.footer')
<script>
    laydate.render({
        elem: '#test16'
        ,range: true
    });
</script>