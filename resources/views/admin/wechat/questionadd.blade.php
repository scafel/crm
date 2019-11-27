@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加客户</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="add">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>问卷名称：</label>
                </div>
                <div class="field">
                    <input id="wechatname" type="text" class="input w50" value="" name="question_name" data-validate="required:请输入问卷名称" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="addQuestionTitle">

            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-danger icon-check-square-o" type="button" onclick="addQuestionName()">添加题目和答案</button>
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
        var num    =   $(".addQuestionTitle .questiontitle").length;
        var str = "<div class=\"form-group questiontitle  questiontitle"+num+"\">\n" +
            "                <div class=\"label\">\n" +
            "                    <label>第"+(num+1)+"题：</label>\n" +
            "                </div>\n" +
            "                <div class=\"field\">\n" +
            "                    <input type=\"text\" class=\"input w50\" value=\"\" name=\"question_title[]\"/>\n" +
            "                    <div class=\"tips\"></div>" +
            "                   <button class=\"button\" type=\"button\" onclick='addQuestionAnswer("+num+")'> 添加答案及分数</button>"+
            "                   <button class=\"button\" type=\"button\" onclick='delTitle("+num+")'> <span class='icon-trash-o'></span></button>"+
            "                </div><br>\n" +
            "                 <hr><div class='addQuestionAnswer"+num+"'></div>" +
            "            </div>"
        $(".addQuestionTitle").append(str);
    }
    function addQuestionAnswer(num) {
        var num1    =   $(".addQuestionTitle .questionanswer").length;
        var str = " <div class=\"field questionanswer questionanswer"+num1+"\" style='float: left;display: inline-block;width: 50px;'>\n" +
            "                    <input type=\"text\" value=\"\" name=\"question_answer"+num+"[]\" /><br />\n" +
            "                    <input type=\"text\" value=\"\" name=\"question_code"+num+"[]\" />\n" +
            "                   <button class=\"button\" type=\"button\" onclick='delAnswer("+num+","+num1+")'><span class='icon-trash-o'></span></button>"+
            "                </div>"
        $(".addQuestionAnswer"+num).append(str);
    }
    function delTitle(num) {
        var e = $(".questiontitle"+num+" input");
        e.val("");
        $(".questiontitle"+num).hide();
    }
    function delAnswer(title,answer) {
        var e = $(".questiontitle"+title+" .questionanswer"+answer+" input");
        e.val("");
        $(".questiontitle"+title+" .questionanswer"+answer).hide();
    }
</script>