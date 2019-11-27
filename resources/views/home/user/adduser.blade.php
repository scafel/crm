@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/user/adduser') }}" method="post" class="form-x">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>姓名：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50 username" value="" name="username" data-validate="required:请输入患者姓名,chinese:姓名应该为中文" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>性别：</label>
                </div>
                <div class="field">
                    <select name="gander" class="input w50 gander">
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
                    <input type="number" class="input w50 age" value="0" name="age" data-validate="required:年龄不能为空,number:年龄必须为数字" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>电话：</label>
                </div>
                <div class="field">
                    <input type="tel" class="input w50 tel" value="0" name="tel" data-validate="required:请输入联系电话,tel:必须为电话号码"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>住址：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50 addr" value="" name="addr"   />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>就诊科室：</label>
                </div>
                <div class="field">
                    <select name="department_id" class="input w50 department_id" required>
                        <option value="">请选择</option>
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
                    <select name="channel_id" class="input w50 channel_id" required>
                        <option value="">请选择</option>
                        @foreach($channel as $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>备注：</label>
                </div>
                <div class="field">
                    <textarea class="input" name="remarks" style=" height:90px;">无</textarea>
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
</script>