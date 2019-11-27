@include('home.common.header')
<body>
<div class="panel admin-panel">
    <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加关键词</strong></div>
    <div class="body-content">
        <form id="form" method="post" class="form-x" action="add">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="label">
                    <label>微信端输入词：</label>
                </div>
                <div class="field">
                    <input type="text" class="input w50" value="" name="keyname" data-validate="required:请输入关键词" />
                    <div class="tips"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>回复类型：</label>
                </div>
                <div class="field">
                    <select name="returntype" class="input w50" onchange="changRturnType(this)">
                        <option value="">请选择回复类型</option>
                        <option value="0">文本</option>
                        <option value="1">图片</option>
                        <option value="2">图文/链接</option>
                    </select>
                    <div class="tips"></div>
                </div>
            </div>
            <div class="returnSelect">

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
    function changRturnType(evt) {
        var typel    =   $(evt).val();
        switch (Number(typel)) {
            case 0:
                showTextMessage();
                break;
            case 1:
                showSelectMessage();
                break;
            case 2:
                showSelectImage();
                break;
            default :
                return false;
        }
    }
    function showTextMessage() {
        $(".returnSelect").empty();
        var str = "<div class=\"form-group\">\n" +
            "                <div class=\"label\">\n" +
            "                    <label>回复内容：</label>\n" +
            "                </div>\n" +
            "                <div class=\"field\">\n" +
            "<textarea name=\"message\" cols=\"30\" rows=\"10\"></textarea>"+
            "                    <div class=\"tips\"></div>\n" +
            "                </div>\n" +
            "            </div>"
        $(".returnSelect").append(str);
    }
    function showSelectMessage() {
        $(".returnSelect").empty();
        var str =   "<div class=\"form-group\">\n" +
            "        <div class=\"label\">\n" +
            "          <label>上传图片：</label>\n" +
            "        </div>\n" +
            "        <div class=\"field\">\n" +
            "          <input type=\"text\" id=\"url1\" name=\"image\" class=\"input tips\" style=\"width:25%; float:left;\" value=\"\" data-toggle=\"hover\" data-place=\"right\" data-image=\"\"  />\n" +
            "          <input type=\"button\" class=\"button bg-blue margin-left\" onclick=\"javascript:$('#file').click();\" value=\"+ 浏览上传\" >\n" +
            "          <input type='file' style='display: none' name='file' id='file' onchange=\"javascript:fileUpload('file','uploadMaterial','url1');\">"+
            "        </div>\n" +
            "      </div>";
        $(".returnSelect").append(str);
    }
    function showSelectImage() {
        $(".returnSelect").empty();
        var str = "<div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>标题：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"url\" type=\"text\" class=\"input w50\" value=\"\" name=\"title\" />\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                </div>\n" +
            "                <div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>简介：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <textarea id=\"remarks\" class=\"input\" name=\"message\" style=\" height:90px;\">无</textarea>\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                </div>\n" +
            "                <div class=\"form-group\">\n" +
            "                    <div class=\"label\">\n" +
            "                        <label>跳转网址：</label>\n" +
            "                    </div>\n" +
            "                    <div class=\"field\">\n" +
            "                        <input id=\"url\" type=\"text\" class=\"input w50\" value=\"\" name=\"url\" />\n" +
            "                        <div class=\"tips\"></div>\n" +
            "                    </div>\n" +
            "                </div>";
        $(".returnSelect").append(str);
    }
</script>