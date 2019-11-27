@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加分值对照</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="/wechat/{{ $wechat_id }}/admin/question/addtips">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="question_id" value="{{ $question->question_id }}">
            <input type="hidden" name="question_token" value="{{ idMd5Token($question->question_id) }}">
            <div class="form-group">
                <div class="label">
                    <label>问卷名称：</label>
                </div>
                <div class="field">
                    <input id="wechatname" type="text" class="input w50" value="{{ $question->question_name }}" name="question_name" data-validate="required:请输入问卷名称" readonly/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>大标题：</label>
                </div>
                <div class="field">
                    <input id="wechatname" type="text" class="input w50" value="@if(!empty($tips)){{ $tips->title }}@endif" name="title" data-validate="required:请输入大标题"/>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="addQuestionTitle">
                @if(!empty($tips))
                    <input type="hidden" name="id[]" value="{{ $tips->id }}">
                    @foreach(unserialize($tips->message) as $key=>$value)
                        <div class="addQuestionTitleLittle addQuestionTitleLittle{{ $key }}">
                            <hr>{{ $key }}
                            <div class="form-group">
                                <div class="label"><label>小标题：</label></div>
                                <div class="field">
                                    <input id="appid" type="text" class="input w50" value="{{ $value['title'] }}" name="smalltitle[]" data-validate="required:不能为空" required/>
                                    <div class="tips"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label"><label>内容：</label></div>
                                <div class="field">
                                    <textarea class="input" name="smalltitlemessage[]" style=" height:90px;">{{ $value['message'] }}</textarea>
                                    <div class="tips">例：健康</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label"><label></label></div>
                                <div class="field">
                                    <button class="button bg-danger icon-check-square-o" type="button" onclick="delCode({{ $key }})">删除该选项</button>
                                </div>
                            </div>
                <hr></div>
                    @endforeach
                @endif
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-danger icon-check-square-o" type="button" onclick="addQuestionName()">添加分值信息</button>
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
    function addQuestionName() {
        var num    =   $(".addQuestionTitle .addQuestionTitleLittle").length;
        var str = "<div class=\"addQuestionTitleLittle addQuestionTitleLittle"+num+"\">\n" +
            "                    <hr>"+num+"\n" +
            "                <div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>小标题：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"appid\" type=\"text\" class=\"input w50\" value=\"\" name=\"smalltitle[]\" data-validate=\"required:不能为空\" required />\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                </div><div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>内容：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                       <textarea class=\"input\" name=\"smalltitlemessage[]\" style=\" height:90px;\"></textarea>\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                    <div class=\"form-group\">\n" +
            "                        <div class=\"label\">\n" +
            "                            <label></label>\n" +
            "                        </div>\n" +
            "                        <div class=\"field\">\n" +
            "                            <button class=\"button bg-danger icon-check-square-o\" type=\"button\" onclick=\"delCode("+num+")\">删除该选项</button>\n" +
            "                        </div>\n" +
            "                    </div>\n" +
            "                    <hr>\n" +
            "                </div>";
        $(".addQuestionTitle").append(str);
    }
    function delCode(num) {
        var e = $(".addQuestionTitleLittle"+num+" input");
        e.val("");
        $(".addQuestionTitleLittle"+num).hide();
    }
</script>