<div style="text-align:center;">
    <p>来源:<a href="http://www.scafel.top/" target="_blank">scafel个人</a></p>
</div>
<div class="fixed-bottom-left">
    <table class="table table-hover text-center" style="cursor:pointer;">
        <tr onclick="showWebMessage(0)">
            <th>您有 <font color="red" class="scafel_message">{{ getToUserMessageToday() }}</font> 条今日的消息</th>
        </tr>
        <tr onclick="showWebMessage(-1)">
            <th>您有 <font color="red" class="scafel_message">{{ getToUserMessageTomorrow() }}</font> 条明日的消息</th>
        </tr>
        <tr onclick="showWebMessage(1)">
            <th>您有 <font color="red" class="scafel_message">{{ getToUserMessage() }}</font> 条消息</th>
        </tr>
    </table>
</div>
</body>
</html>
<script>
    function showWebMessage(message) {
        layer.open({
            type: 2,
            title: "站内消息",
            area:["300px",'500px'],
            shade:0,
            anim: 2,
            fixed:true,
            resize:false,
            move: false,
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/notepad') }}/type/"+message,
            offset: 'lb',
        })
    }
    function openUrlR(id,name) {
        layer.open({
            type: 2,
            title: name,
            shade:0,
            area:['500px','500px'],
            closeBtn: 1,
            shadeClose: true,
            content:"{{ url('home/page/notepad/readnote') }}/"+id+"/token",
            success:function () {
                $.get('{{ url("home/page/notepad/toread") }}/'+id+'/token');
            }
        })
    }
</script>