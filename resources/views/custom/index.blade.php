@include("home.common.header")

@include("home.common.footer")
<script>
    ws = new WebSocket("ws://127.0.0.1:20480");
    ws.onopen = function() {
        alert("连接成功");
        ws.send('tom');
    };
    ws.onmessage = function(e) {
        alert("收到服务端的消息：" + e.data);
    };

</script>