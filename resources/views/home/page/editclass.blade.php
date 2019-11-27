@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span></strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/class/edit') }}" class="form-x"  method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $class->id }}">
            <div class="form-group">
                <div class="label">
                    <label>输入名称：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="{{ $class->name }}" name="name" data-validate="required:请输入名称" />
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