<script>
    var wsClient = null; //WS客户端对象
    window.onload = function(){
        //连接到WS服务器，注意：协议名不是http！
        wsClient = new WebSocket('ws://127.0.0.1:20480');
        wsClient.onopen = function(){
            console.log('WS客户端已经成功连接到服务器上')
        }
    }
    btSendAndReceive.onclick = sendMessage;
    function sendMessage(){
        //向WS服务器发送一个消息
        wsClient.send('Hello Server this is scafel');
        //接收WS服务器返回的消息
        wsClient.onmessage = function(e){
            console.log('WS客户端接收到一个服务器的消息：'+ e.data);
            val.innerHTML=e.data;
        }
    }
    btClose.onclick = closeLink;
    function closeLink(){
        //断开到WS服务器的连接
        wsClient.close();  //向服务器发消息，主动断开连接
        wsClient.onclose = function(){
            //经过客户端和服务器的四次挥手后，二者的连接断开了
            console.log('到服务器的连接已经断开')
        }
    }
</script>