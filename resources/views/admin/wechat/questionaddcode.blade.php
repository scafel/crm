@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加分值对照</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="/wechat/{{ $wechat_id }}/admin/question/addcode">
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
            <div class="addQuestionTitle">
                @if(!empty($codelist))
                    @foreach($codelist as $key=>$value)
                        <input type="hidden" name="id[]" value="{{ $value->id }}">
                        <div class="addQuestionTitleLittle addQuestionTitleLittle{{ $key }}">
                            <hr>{{ $key }}
                            <div class="form-group">
                                <div class="label">
                                    <label>分值范围：</label></div>
                                <div class="field">
                                    <input id="appid" type="text" class="input w50" value="{{ $value->range }}" name="range[]" data-validate="required:不能为空" required/>
                                    <div class="tips">例：0-9</div></div></div>
                            <div class="form-group">
                                <div class="label">
                                    <label>健康程度：</label></div>
                                <div class="field">
                                    <input id="appid" type="text" class="input w50" value="{{ $value->title }}" name="title[]" data-validate="required:不能为空" required/>
                                    <div class="tips">例：健康</div></div></div>
                            <div class="form-group">
                                <div class="label">
                                    <label>症状描述：</label></div>
                                <div class="field">
                                    <input id="appid" type="text" class="input w50" value="{{ $value->answer }}" name="answer[]" data-validate="required:不能为空" required/>
                                    <div class="tips"></div>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="label">
                            <label>分值最大值：</label></div>
                            <div class="field">
                            <input id="appid" type="text" class="input w50" value="{{ $value->code }}" name="code[]" data-validate="required:不能为空" required/>
                            <div class="tips">例子： 分值范围为 0-9 则填 9</div></div></div>
                            <div class="form-group">
                            <div class="label">
                            <label></label></div>
                            <div class="field">
                            <button class="button bg-danger icon-check-square-o" type="button" onclick="delCode({{ $key }})">删除该选项</button></div></div>
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
            "                        <label>分值范围：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"appid\" type=\"text\" class=\"input w50\" value=\"\" name=\"range[]\" data-validate=\"required:不能为空\" required />\n" +
            "                        <div class=\"tips\">例：0-9</div>\n" +
            "                    </div>\n" +
            "                </div><div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>健康程度：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"appid\" type=\"text\" class=\"input w50\" value=\"\" name=\"title[]\" data-validate=\"required:不能为空\"  required/>\n" +
            "                        <div class=\"tips\">例：健康</div>\n" +
            "                    </div>\n" +
            "                </div><div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>症状描述：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"appid\" type=\"text\" class=\"input w50\" value=\"\" name=\"answer[]\"  data-validate=\"required:不能为空\" required/>\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                </div><div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>分值最大值：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"appid\" type=\"text\" class=\"input w50\" value=\"\" name=\"code[]\"  data-validate=\"required:不能为空\" required/>\n" +
            "                        <div class=\"tips\">例子： 分值范围为 0-9 则填 9</div>\n" +
            "                    </div>\n" +
            "                </div>\n" +
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