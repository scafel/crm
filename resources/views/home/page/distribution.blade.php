@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o">不显示的不能作为选项</span></strong></div>
    <div class="body-content">
        <form id="form" action="{{ url('home/page/class/distribution') }}" class="form-x"  method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>请选择等级：</label>
                </div>
                <div class="field" style="padding-top:8px;">
                    @if(empty($class))
                        没有任何项目选择项目
                    @else
                        @foreach($class as $value)
                            <label for="role_id_{{ $value->id }}">
                                {{ $value->name }}
                                @if($value->status)
                                    <input name="class_id" value="{{ $value->id }}" type="radio" />
                                @else
                                    <input name="class_id" value="{{ $value->id }}"  type="radio" disabled="disabled"/>
                                @endif
                            </label>&nbsp;&nbsp;&nbsp;
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>选择用户（可多选）：</label>
                </div>
                <div class="field">
                    <a class="button border-main" href="javascript:void(0);" onclick="selectUser()"><span class="icon-edit"></span> 选择用户</a>
                    <div class="tips"></div>
                    <div class="field scafel_user_distribution">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>选择历史服务条目（可多选）：</label>
                </div>
                <div class="field">
                    <a class="button border-main" href="javascript:void(0);" onclick="selectCustom()"><span class="icon-edit"></span> 选择服务条目</a>
                    <div class="tips"></div>
                    <div class="field scafel_user_distribution_custom">
                    </div>
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
    function selectUser() {
        layer.open({
            type: 2,
            shade: false,
            area: ['500px','500px'],
            maxmin: true,
            content: "{{ url('home/page/user/show') }}",
        })
    }
    function selectCustom() {
        layer.open({
            type: 2,
            shade: false,
            area: ['500px','500px'],
            maxmin: true,
            content: "{{ url('home/page/custom/show') }}",
        })
    }
    function setCustomMessage(id,name,type) {
        var  html   =   "<div class=\"tips scafel_user_distribution_custom_id_"+id+"\" style=\"\"> \n" +
            "   <input type='hidden' name='custom_id[]' value='"+id+"'>"+
            "                            <a class=\"button border-info\" href=\"javascript:void(0);\" >"+name+"</a>||\n" +
            "                            <span onclick=\"setCustomMessage("+id+",'"+name+"',0)\"> 删除</span>\n" +
            "                        </div>";
        if (type){
            $(".scafel_user_distribution_custom").append(html);
        } else{
            $(".scafel_user_distribution_custom_id_"+id).remove();
        }
    }
    function setUserMessage(id,name,type) {
        var  html   =   "<div class=\"tips scafel_user_distribution_id_"+id+"\" style=\"\"> \n" +
            "   <input type='hidden' name='user_id[]' value='"+id+"'>"+
            "                            <a class=\"button border-info\" href=\"javascript:void(0);\" >"+name+"</a>||\n" +
            "                            <span onclick=\"setUserMessage("+id+",'"+name+"',0)\"> 删除</span>\n" +
            "                        </div>";
        if (type){
            $(".scafel_user_distribution").append(html);
        } else{
            $(".scafel_user_distribution_id_"+id).remove();
        }
    }
</script>