@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o">不显示的不能作为选项</span></strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/department/merge') }}" class="form-x"  method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>选择要合并的科室：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    @if(empty($list))
                        没有任何项目选择项目
                    @else
                        @foreach($list as $value)
                                <label for="role_id_{{ $value->id }}">
                                    {{ $value->name }}
                                    @if($value->status)
                                        <input name="department_id[]" value="{{ $value->id }}" onchange="changeSelect(this,'{{ $value->id }}','{{ $value->name }}')"  type="checkbox" />
                                    @else
                                        <input name="department_id[]" value="{{ $value->id }}"  type="checkbox" disabled="disabled"/>
                                    @endif
                                </label>&nbsp;&nbsp;&nbsp;
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>选择合并后的科室：</label>
                </div>
                <div class="field">
                    <select name="mergedepartment_id" class="input w50" id="department_id" required>
                        <option value="">请先选择要合并项目</option>

                    </select>
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
    function changeSelect(event,id,name) {
        if($(event).is(":checked")){
            $("#department_id").append("<option id='department_"+id+"' value=\""+id+"\">"+name+"</option>");
        }else{
            $("#department_"+id).remove();
        }
    }
</script>