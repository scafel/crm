@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        {{--<form id="form" method="post" class="form-x" action="javascript:formSubmit('form','{{ url('home/page/user/addadmin') }}',2);">--}}
        <form id="form" action="{{ url('home/page/user/addadmin') }}" class="form-x"  method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>姓名：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="name" data-validate="required:请输入管理员姓名,chinese:姓名应该为中文" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>账号：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="username" data-validate="required:请输入账号" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>密码：</label>
                </div>
                <div class="field">
                    <input type="password" class="input w50" value="" name="password" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>选择权限：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    @if(empty($role))
                        没有任何权限选择项目
                    @else
                        @foreach($role as $value)
                            <label for="role_id_{{ $value->id }}">
                                {{ $value->role_name }}
                                <input id="role_id_{{ $value->id }}" name="role_id[]" value="{{ $value->id }}" class="top{{ $value->id }}"  type="checkbox" onclick="selectAllChild(this)"/>
                            </label>
                            <br/><hr/>
                            @foreach($value->child as $v)
                                <label for="role_id_{{ $v->id }}">
                                    {{ $v->role_name }}
                                    <input id="role_id_{{ $v->id }}" name="role_id[]" value="{{ $v->id }}" class="child{{ $value->id }}" data-top="{{ $value->id }}" onclick="selectTop(this)"  type="checkbox" />
                                </label>&nbsp;&nbsp;&nbsp;
                            @endforeach
                            <br/><hr/>
                        @endforeach
                    @endif
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
function selectAllChild(evt) {$(".child"+$(evt).val()).prop("checked",$(evt).is(':checked'));}
function selectTop(evt) {
    var top = $(evt).attr("data-top");
    if($(evt).is(":checked")){
        $(".top"+top).is(":checked")?"":$(".top"+top).prop("checked",true);
    }else{
        var child = $(".child"+top);
        var num = 0;
        child.each(function (index,element) {
            $(element).is(":checked")?num++:"";
        })
        num >0 ?$(".top"+top).prop("checked",true):$(".top"+top).prop("checked",false);
    }
}
</script>