<script>
    /**
     * 处理浏览器关闭后消息的传出和接受问题
     */
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
    var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera浏览器
    var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera; //判断是否IE浏览器
    var isIE11 = userAgent.indexOf("rv:11.0") > -1; //判断是否是IE11浏览器
    var isEdge = userAgent.indexOf("Edge") > -1 && !isIE; //判断是否IE的Edge浏览器
    if(!isIE && !isEdge && !isIE11) {//兼容chrome和firefox
        var _beforeUnload_time = 0, _gap_time = 0;
        var is_fireFox = navigator.userAgent.indexOf("Firefox")>-1;//是否是火狐浏览器
        window.onunload = function (){
            _gap_time = new Date().getTime() - _beforeUnload_time;
            if(_gap_time <= 5){
                closeLink();
            }else{//浏览器刷新
            }
        }
        window.onbeforeunload = function (){
            _beforeUnload_time = new Date().getTime();
            if(is_fireFox){//火狐关闭执行
                closeLink();
            }
        };
    }
    if(isIE) {//兼容ie8,9,10
        window.onbeforeunload = closeLink;
        window.onunload = closeLink;
    }
    /**
     * 监听回车键按下事件
     */
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            sendMessage();
        }
    };
</script>